<?php

namespace App\Repositories;

use App\Models\NotComeDay;
use Illuminate\Support\Facades\DB;

class NotComeDaysRepository
{
    public function add($arr){
        NotComeDay::insert($arr);
    }

    public function getTotalAbsentDays($subjectId, $month) {
        $data = NotComeDay::query()
            ->with(['student' => function ($query) {
                $query->select('id','name');
            }])
            ->select('student_id', DB::raw('COUNT(*) as total_absent_days'))
            ->where('subject_id', $subjectId)
            ->whereMonth('date', $month)
            ->groupBy('student_id')
            ->get();


        return $data;
    }

    public function getWithStudentName($id){
        return NotComeDay::query()
            ->with(['student' => function ($query) {
                $query->select('id','name');
            }])
            ->where('attendance_id', $id)
            ->get();
    }
}
