<?php

namespace App\Repositories;

use App\Models\Attendance;

class AttendanceRepository
{
    public function getAttendanceBySubjectId($id){
        return Attendance::where('subject_id', $id)
            ->where('teacher_id', session('id'))
            ->orderBy('date','asc')
            ->get();
    }

    public function getAttendanceBySubjectIdDate($sb_id, $date){
        return Attendance::where('subject_id', $sb_id,)
            ->where('date', $date)
            ->first();
    }

    public function add($sb_id,$tch_id,$d,$c){
        $attendance = new Attendance;
        $attendance->subject_id = $sb_id;
        $attendance->teacher_id = $tch_id;
        $attendance->date = $d;
        $attendance->not_come_students_count = $c;
        $attendance->save();
        return $attendance->id;
    }
}
