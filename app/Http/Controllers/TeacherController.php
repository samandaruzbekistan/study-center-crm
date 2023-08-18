<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AttachRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\MonthlyPaymentRepository;
use App\Repositories\NotComeDaysRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct(
        protected TeacherRepository $teacherRepository,
        protected SubjectRepository $subjectRepository,
        protected AttachRepository $attachRepository,
        protected MonthlyPaymentRepository $monthlyPaymentRepository,
        protected AttendanceRepository $attendanceRepository,
        protected NotComeDaysRepository $notComeDaysRepository,
    )
    {
    }


    //    Auth
    public function auth(LoginRequest $request){
        $teacher = $this->teacherRepository->getTeacher($request->username);
        if (!$teacher){
            return back()->with('login_error', 1);
        }
        if (Hash::check($request->input('password'), $teacher->password)) {
            session()->put('teacher',1);
            session()->put('name',$teacher->name);
            session()->put('id',$teacher->id);
            session()->put('photo',$teacher->photo);
            session()->put('username',$teacher->username);
            return redirect()->route('teacher.home');
        }
        else{
            return back()->with('login_error', 1);
        }
    }

    public function home(){
        $subjects = $this->subjectRepository->getTeacherSubjects(session('id'));
        return view('teacher.subjects', ['subjects' => $subjects]);
    }

    public function logout(){
        session()->flush();
        return redirect()->route('teacher.login');
    }

    public function profile(){
        $admin = $this->teacherRepository->getTeacher(session('username'));
        return view('teacher.profile', ['user' => $admin]);
    }

    public function update(Request $request){
        $request->validate([
            'password1' => 'required|string',
            'password2' => 'required|string',
            'username' => 'required|string',
        ]);
        if ($request->input('password1') != $request->input('password2')) return back()->with('password_error',1);
        $this->teacherRepository->update_password($request->password1, $request->username);
        return back()->with('success',1);
    }

    public function update_avatar(Request $request){
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);
        $cashier = $this->teacherRepository->getTeacher(session('username'));
        if ($cashier->photo != 'no_photo.jpg') unlink('img/avatars/'.$cashier->photo);
        $file = $request->file('photo')->extension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        session()->put('photo', $photo_name);
        $path = $request->file('photo')->move('img/avatars/',$photo_name);
        $this->teacherRepository->update_photo($photo_name);
        return back()->with('success_photo',1);
    }



    public function subject_detail($id){
        $subject = $this->subjectRepository->getSubject($id);
        if ($subject->teacher_id != session('id')) return redirect()->route('teacher.login');
        $attach = $this->attachRepository->getAttachWithStudentsAndTeacher($id);
        if (count($attach) < 1) return back()->with('attach_error',1);
        $payments = $this->monthlyPaymentRepository->monthPaymentsBySubjectId($id);
        $payments_success = $this->monthlyPaymentRepository->getPaidPaymentsByMonth($id);
        return view('teacher.subject_detail',['attachs' => $attach, 'payments' => $payments,'payments_success' => $payments_success]);
    }

    public function payment_details(Request $request){
        return $this->monthlyPaymentRepository->getPaymentsByMonth($request->month, $request->subject_id);
    }

    public function attendances($subject_id){
        $attachs = $this->attachRepository->getAttachWithStudentsAndTeacher($subject_id);
        $attendances = $this->attendanceRepository->getAttendanceBySubjectId($subject_id, '09');
        $absentDay = $this->notComeDaysRepository->getTotalAbsentDays($subject_id,'09');
//        return ['attendances' => $attendances, 'attachs' => $attachs, 'subject_id' => $subject_id];
        return view('teacher.attendances', ['absentDay'=> $absentDay,'attendances' => $attendances, 'attachs' => $attachs, 'subject_id' => $subject_id]);
    }

    public function attendance(){
        $subjects = $this->subjectRepository->getTeacherSubjects(session('id'));
        return view('teacher.attendance', ['subjects' => $subjects]);
    }

    public function attendance_detail($subject_id, $month){
        $attendances = $this->attendanceRepository->getAttendanceBySubjectId($subject_id, $month);
        $absentDay = $this->notComeDaysRepository->getTotalAbsentDays($subject_id,$month);
        return [$absentDay,$attendances];
    }

    public function attendance_detail_day($id){
        return $this->notComeDaysRepository->getWithStudentName($id);
    }


    public function attendance_check(Request $request){
        $selectedStudentIds= $request->input('student_ids', []);
        $d = date('Y-m-d');
        $c= count($selectedStudentIds);
        $att = $this->attendanceRepository->getAttendanceBySubjectIdDate($request->subject_id, $d);
        if ($att) return back()->with('error',1);
        $attendance_id = $this->attendanceRepository->add($request->subject_id,session('id'), $d,$c);
        $inserted_row = [];
        foreach ($selectedStudentIds as $id){
            $inserted_row[] = [
                'student_id' => $id,
                'subject_id' => $request->subject_id,
                'date' => $d,
                'attendance_id' => $attendance_id
            ];
        }
        $this->notComeDaysRepository->add($inserted_row);
        return back()->with('success',1);
    }





}
