<?php

namespace App\Http\Controllers;

use App\Exports\DebtTableExport;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\DebtTableService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DebtTableController extends Controller
{
    public function __construct(protected DebtTableService $debtTableService)
    {
    }

    public function cashierIndex(Request $request)
    {
        $data = $this->debtTableService->build(
            $request->get('from'),
            $request->get('to'),
            $request->get('subject_id'),
            $request->get('teacher_id')
        );

        $data['subjectList'] = Subject::query()
            ->when($data['teacherId'], fn ($q) => $q->where('teacher_id', $data['teacherId']))
            ->orderBy('name')
            ->get(['id', 'name']);
        $data['teacherList'] = Teacher::orderBy('name')->get(['id', 'name']);

        return view('cashier.debt_table', $data + [
            'indexRoute'  => 'cashier.debt.table',
            'exportRoute' => 'cashier.debt.table.export',
        ]);
    }

    public function teacherIndex(Request $request)
    {
        $teacherId = session('id');

        $data = $this->debtTableService->build(
            $request->get('from'),
            $request->get('to'),
            $request->get('subject_id'),
            $teacherId
        );

        $data['subjectList'] = Subject::where('teacher_id', $teacherId)->orderBy('name')->get(['id', 'name']);
        $data['teacherList'] = null;

        return view('teacher.debt_table', $data + [
            'indexRoute'  => 'teacher.debt.table',
            'exportRoute' => 'teacher.debt.table.export',
        ]);
    }

    public function cashierExport(Request $request)
    {
        return $this->export($request, $request->get('teacher_id'));
    }

    public function teacherExport(Request $request)
    {
        // O'qituvchi faqat o'z guruhlarini yuklab oladi.
        return $this->export($request, session('id'));
    }

    private function export(Request $request, $teacherId)
    {
        $data = $this->debtTableService->build(
            $request->get('from'),
            $request->get('to'),
            $request->get('subject_id'),
            $teacherId
        );

        $filename = 'qarzdorlar_' . $data['from'] . '_' . $data['to'] . '.xlsx';

        return Excel::download(new DebtTableExport($data), $filename);
    }
}
