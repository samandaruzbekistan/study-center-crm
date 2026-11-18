<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DebtTableExport implements FromView, ShouldAutoSize
{
    public function __construct(protected array $data)
    {
    }

    public function view(): View
    {
        return view('exports.debt_table', $this->data);
    }
}
