<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getStudents(){
        return Student::orderBy('name', 'asc')->paginate(100);
    }

    public function getStudentsAll(){
        return Student::all();
    }
    public function getStudentById($id){
        return Student::find($id);
    }

    public function getStudentByName($name){
        return Student::where('name', $name)->first();
    }

    public function addStudentCashier($name, $phone, $phone2,$r_id,$d_id,$q_id){
        $st = new Student;
        $st->name = $name;
        $st->region_id = $r_id;
        $st->district_id = $d_id;
        $st->quarter_id = $q_id;
        $st->phone = '998'.$phone;
        $st->parents_phone = '998'.$phone2;
        $st->cashier_id = session('id');
        $st->save();
        return $st->id;
    }

    public function addStudentTeacher($name, $phone, $phone2,$r_id,$d_id,$q_id){
        $st = new Student;
        $st->name = $name;
        $st->region_id = $r_id;
        $st->district_id = $d_id;
        $st->quarter_id = $q_id;
        $st->phone = '998'.$phone;
        $st->parents_phone = '998'.$phone2;
        $st->teacher_id = session('id');
        $st->save();
        return $st->id;
    }

    public function getStudentsByName($name){
        if ($name == '') return [];
        $users = Student::whereRaw('LOWER(students.name) LIKE ?', ['%' . strtolower($name) . '%'])
            ->get();
        return $users;
    }

    public function getTeacherStudents(){
        return Student::orderBy('name', 'asc')->where('teacher_id', session('id'))->get();
    }


    public function getStudentWithSubjectsPayments($studentId){
        return Student::with('attachs', 'monthlyPayments')->find($studentId);
    }


}
