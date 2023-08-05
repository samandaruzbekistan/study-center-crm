<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getStudents(){
        return Student::orderBy('name', 'asc')->paginate(100);
    }

    public function getStudentById($id){
        return Student::find($id);
    }

    public function getStudentByName($name){
        return Student::where('name', $name)->first();
    }

    public function addStudentCashier($name, $phone){
        $st = new Student;
        $st->name = $name;
        $st->phone = '998'.$phone;
        $st->cashier_id = session('id');
        $st->save();
    }

    public function getStudentsByName($name){
        if ($name == '') return [];
        $users = Student::whereRaw('LOWER(students.name) LIKE ?', ['%' . strtolower($name) . '%'])
            ->get();
        return $users;
    }
}
