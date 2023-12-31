<?php

namespace App\Repositories;

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class TeacherRepository
{
    public function getTeachers(){
        return Teacher::orderBy('name', 'asc')->get();
    }

    public function getTeacher($username){
        return Teacher::where('username', $username)->first();
    }

    public function addTeacher($name, $subject, $phone, $username, $password, $photo){
        $teacher = new Teacher;
        $teacher->name = $name;
        $teacher->subject = $subject;
        $teacher->phone = '998'.$phone;
        $teacher->username = $username;
        $teacher->password = Hash::make($password);
        $teacher->photo = $photo;
        $teacher->save();
    }

    public function update_password($password, $username){
        Teacher::where('username', $username)->update(['password' => Hash::make($password)]);
    }

    public function getTeacherWithSubjects($teacherId)
    {
        $teacher = Teacher::with('subjects')->find($teacherId);
        return $teacher;
    }

    public function update_photo($photo){
        Teacher::where('id', session('id'))->update(['photo' => $photo]);
    }

    public function allTeachersWithSubjects()
    {
        $teacher = Teacher::with('subjects')->get();
        return $teacher;
    }
}
