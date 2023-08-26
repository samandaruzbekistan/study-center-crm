<?php

namespace App\Exports;

use App\Models\MonthlyPayment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{
    public function view(): View
    {
        $payments = MonthlyPayment::with(['student', 'teacher', 'attach'])
            ->where('amount_paid','>',0)
            ->get();

        return view('exports.payments', [
            'payments' => $payments,
        ]);
    }
}
