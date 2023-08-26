<?php

namespace App\Exports;

use App\Models\MonthlyPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class DebtExportTeacher implements FromView
{
    public function view(): View
    {
        $payments = MonthlyPayment::with(['student', 'teacher', 'attach'])
            ->where('amount','>',0)
            ->where('teacher_id', session('id'))
            ->get();

        return view('exports.debt', [
            'payments' => $payments,
        ]);
    }
}
