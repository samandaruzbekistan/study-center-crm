<?php

namespace App\Services;

use App\Models\MonthlyPayment;
use Carbon\Carbon;

class DebtTableService
{
    private const MONTH_NAMES = [
        1 => 'Yanvar', 2 => 'Fevral', 3 => 'Mart', 4 => 'Aprel',
        5 => 'May', 6 => 'Iyun', 7 => 'Iyul', 8 => 'Avgust',
        9 => 'Sentabr', 10 => 'Oktabr', 11 => 'Noyabr', 12 => 'Dekabr',
    ];

    /**
     * Tanlangan davr uchun qarzdorlar jadvalini yig'adi:
     * har bir o'quvchi + guruh - bitta qator, har bir oy - bitta ustun.
     *
     * Qarz: monthly_payments.status = 0 va amount > 0 (qisman to'lovda
     * amount qolgan qarzga yangilanadi). To'lovlar oldindan kelajak oylar
     * uchun ham yaratilgani sababli joriy oydan keyingi oylar qarz emas.
     */
    public function build(?string $from = null, ?string $to = null, $subjectId = null, $teacherId = null): array
    {
        $subjectId = is_numeric($subjectId) ? (int) $subjectId : null;
        $teacherId = is_numeric($teacherId) ? (int) $teacherId : null;

        [$start, $end] = $this->resolveRange($from, $to, $teacherId);

        $months = [];
        for ($m = $start->copy(); $m->lte($end); $m->addMonth()) {
            $months[] = $m->format('Y-m-d');
        }

        $payments = MonthlyPayment::with([
                'student:id,name,phone,parents_phone',
                'subject:id,name',
                'teacher:id,name',
            ])
            ->whereBetween('month', [$start->format('Y-m-d'), $end->copy()->endOfMonth()->format('Y-m-d')])
            ->where('status', 0)
            ->where('amount', '>', 0)
            ->when($subjectId, fn ($q) => $q->where('subject_id', $subjectId))
            ->when($teacherId, fn ($q) => $q->where('teacher_id', $teacherId))
            ->get();

        $rows = [];

        foreach ($payments->groupBy(fn ($p) => $p->student_id . '-' . $p->subject_id) as $items) {
            $first = $items->first();

            $byMonth = array_fill_keys($months, 0);
            foreach ($items as $payment) {
                $key = Carbon::parse($payment->month)->startOfMonth()->format('Y-m-d');
                if (array_key_exists($key, $byMonth)) {
                    $byMonth[$key] += (float) $payment->amount;
                }
            }

            $rows[] = [
                'student_id'    => $first->student_id,
                'student_name'  => $first->student->name ?? 'N/A',
                'phone'         => $this->formatPhone($first->student->phone ?? null),
                'parents_phone' => $this->formatPhone($first->student->parents_phone ?? null),
                'subject_name'  => $first->subject->name ?? '—',
                'teacher_name'  => $first->teacher->name ?? '—',
                'by_month'      => $byMonth,
                'total'         => array_sum($byMonth),
            ];
        }

        usort($rows, function ($a, $b) {
            return [$a['subject_name'], $a['student_name']] <=> [$b['subject_name'], $b['student_name']];
        });

        $monthTotals = array_fill_keys($months, 0);
        foreach ($rows as $row) {
            foreach ($months as $month) {
                $monthTotals[$month] += $row['by_month'][$month];
            }
        }

        $monthLabels = [];
        $spansYears = $start->year !== $end->year;
        foreach ($months as $month) {
            $date = Carbon::parse($month);
            $monthLabels[$month] = self::MONTH_NAMES[$date->month]
                . ($spansYears ? ' ' . $date->year : '');
        }

        return [
            'months'      => $months,
            'monthLabels' => $monthLabels,
            'rows'        => $rows,
            'monthTotals' => $monthTotals,
            'grandTotal'  => array_sum($monthTotals),
            'from'        => $start->format('Y-m'),
            'to'          => $end->format('Y-m'),
            'subjectId'   => $subjectId,
            'teacherId'   => $teacherId,
        ];
    }

    /**
     * Davr berilmagan bo'lsa - eng eski to'lanmagan oydan joriy oygacha.
     * "Gacha" joriy oydan oshmaydi: keyingi oylar hali qarz emas.
     */
    private function resolveRange(?string $from, ?string $to, ?int $teacherId): array
    {
        $currentMonth = Carbon::now('Asia/Tashkent')->startOfMonth();

        $end = $this->parseMonth($to) ?? $currentMonth->copy();
        if ($end->gt($currentMonth)) {
            $end = $currentMonth->copy();
        }

        $start = $this->parseMonth($from) ?? $this->oldestDebtMonth($teacherId) ?? $end->copy();

        if ($end->lt($start)) {
            [$start, $end] = [$end, $start];
        }

        // Juda uzoq davr jadvalni ishlatib bo'lmas holga keltiradi.
        if ($start->diffInMonths($end) > 23) {
            $start = $end->copy()->subMonths(23);
        }

        return [$start, $end];
    }

    private function oldestDebtMonth(?int $teacherId): ?Carbon
    {
        $month = MonthlyPayment::where('status', 0)
            ->where('amount', '>', 0)
            ->where('month', '<=', Carbon::now('Asia/Tashkent')->endOfMonth()->format('Y-m-d'))
            ->when($teacherId, fn ($q) => $q->where('teacher_id', $teacherId))
            ->min('month');

        return $month ? Carbon::parse($month)->startOfMonth() : null;
    }

    /**
     * 998901234567 -> "90 123 45 67".
     * Kutilmagan formatdagi raqam qanday bo'lsa shundayligicha qaytariladi.
     */
    private function formatPhone(?string $phone): string
    {
        $digits = preg_replace('/\D/', '', (string) $phone);

        if ($digits === '') {
            return '';
        }

        if (strlen($digits) === 12 && str_starts_with($digits, '998')) {
            $digits = substr($digits, 3);
        }

        if (strlen($digits) !== 9) {
            return $digits;
        }

        return substr($digits, 0, 2) . ' '
            . substr($digits, 2, 3) . ' '
            . substr($digits, 5, 2) . ' '
            . substr($digits, 7, 2);
    }

    private function parseMonth(?string $value): ?Carbon
    {
        if (!$value || !preg_match('/^\d{4}-\d{2}$/', $value)) {
            return null;
        }

        try {
            return Carbon::parse($value . '-01')->startOfMonth();
        } catch (\Exception $e) {
            return null;
        }
    }
}
