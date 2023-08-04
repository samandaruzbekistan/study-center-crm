<?php

namespace App\Repositories;

use App\Models\Subject;


class SubjectRepository
{
    public function getTeacherSubjects($teacher_id){
        return Subject::where('teacher_id', $teacher_id)->get();
    }

    public function getAllSubjects(){
        return Subject::orderBy('name', 'asc')->get();
    }

    public function getSubject($id){
        return Subject::find($id);
    }

    public function addSubject($name, $price, $l_count, $teacher_id){
        $subject = new Subject;
        $subject->name = $name;
        $subject->price = $price;
        $subject->lessons_count = $l_count;
        $subject->teacher_id = $teacher_id;
        $subject->save();
    }
}
