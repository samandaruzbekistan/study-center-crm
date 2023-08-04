<?php

namespace App\Repositories;

use App\Models\Student;

class StudentRepository
{
    public function getStudents(){
        return Student::orderBy('name', 'asc')->paginate(100);
    }
}
