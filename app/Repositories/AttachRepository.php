<?php

namespace App\Repositories;

use App\Models\Attach;

class AttachRepository
{
    public function getAttachWithStudentsAndTeacher($subject_id){
        return Attach::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name','phone');
            }, 'teacher' => function ($query) {
                $query->select('id', 'name');
            }])->where('subject_id', $subject_id)->where('status', 1)->get();
    }

    public function addAttach($student, $subject,$name,$date){
        $attach = new Attach;
        $attach->student_id = $student;
        $attach->subject_id = $subject;
        $attach->subject_name = $name;
        $attach->date = date('d-m-Y', strtotime($date));
        $attach->save();

        return $attach->getKey();
    }

    public function getAttachBySubjectId($subject_id){
        return Attach::query()
            ->with(['student' => function ($query) {
                $query->select('id', 'name','phone');
            }])->where('subject_id',$subject_id)->get();
    }

    public function getAttach($student, $subject){
        return Attach::where('student_id', $student)
            ->where('subject_id', $subject)->first();
    }

    public function deActiveAttach($id){
        Attach::where('id', $id)->update([
            'status' => 0
        ]);
    }

    public function getAttachStudentId($student_id){
        return Attach::where('student_id', $student_id)->get();
    }

    public function getAttachWithMonthlyPayments($attachId)
    {
        $attach = Attach::with('monthlyPayments')->find($attachId);
        return $attach;
    }
}
