<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AttachRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\MonthlyPaymentRepository;
use App\Repositories\NotComeDaysRepository;
use App\Repositories\SalariesRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Services\SmsService;
use Carbon\Carbon;
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
        protected StudentRepository $studentRepository,
        protected SmsService $smsService,
        protected SalariesRepository $salariesRepository,
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
            session()->flush();
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


    public function students(){
        return view('teacher.students', ['students' => $this->studentRepository->getTeacherStudents()]);
    }

    public function new_student(Request $request){
//        return $request;
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:9',
            'phone2' => 'required|numeric|digits:9',
            'region_id' => 'required|numeric',
            'quarter_id' => 'required|numeric',
            'district_id' => 'required|numeric',
        ]);
        $student = $this->studentRepository->getStudentByName($request->name);
        if (!empty($student)) return back()->with('username_error',1);
        $student_id = $this->studentRepository->addStudentTeacher($request->name, $request->phone,$request->phone2,$request->region_id, $request->district_id,$request->quarter_id);
        return redirect()->route('teacher.student', ['id' => $student_id])->with('success',1);
    }

    public function student($id){
        $student = $this->studentRepository->getStudentWithSubjectsPayments($id);
        return view('teacher.student', ['student' => $student]);
    }


    public function attach(Request $request){
        $request->validate([
            "amount"=> "required|numeric|regex:/^[+-]?\d+(\.\d+)?$/",
            "date"=> "required|date",
            "student_id"=> "required|numeric",
            "teacher_id"=> "required|numeric",
            "subject_id"=> "required|numeric"
        ]);

        $student = $this->studentRepository->getStudentById($request->student_id);
        $subject = $this->subjectRepository->getSubject($request->subject_id);
        $attach = $this->attachRepository->getAttach($request->student_id, $request->subject_id);
        if ($attach) return back()->with('attach_error', 1);
        $attachedSubjectId = $this->attachRepository->addAttach($student->id, $subject->id, $subject->name, $request->date);
        $carbonDate = Carbon::parse($request->date);
        $currentYear = $carbonDate->year;
        $currentMonth = $carbonDate->month;
        $price = $request->amount;
        $daysInMonth = $carbonDate->daysInMonth;
        $firstMonthPrice = ceil(($price - (($price / $daysInMonth) * ($carbonDate->day - 1))) / 1000) * 1000;
        $rowsToInsert = [];
        if ($currentMonth > 8){
            $countdown = 0;
            for ($month = $currentMonth; $month <= 12; $month++){
                $countdown++;
                $row = [
                    'attach_id' => $attachedSubjectId,
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $subject->teacher_id,
                    'amount' => $price,
                    'month' => Carbon::create($currentYear, $month, 1)->format('Y-m-d'),
                ];

                $rowsToInsert[] = $row;
            }
            $currentYear++;
            for ($month = 1; $month <= 8; $month++){
                $row = [
                    'attach_id' => $attachedSubjectId,
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $subject->teacher_id,
                    'amount' => $price,
                    'month' => Carbon::create($currentYear, $month, 1)->format('Y-m-d'),
                ];
                $rowsToInsert[] = $row;
            }
        }
        elseif ($currentMonth <= 8){
            $countdown = 0;
            for ($month = $currentMonth; $month <= 8; $month++){
                $countdown++;
                $row = [
                    'attach_id' => $attachedSubjectId,
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'teacher_id' => $subject->teacher_id,
                    'amount' => $price,
                    'month' => Carbon::create($currentYear, $month, 1)->format('Y-m-d'),
                ];
                $rowsToInsert[] = $row;
            }
        }
        $this->monthlyPaymentRepository->add($rowsToInsert);
        return redirect()->route('teacher.student', ['id' => $student->id])->with('attach',1);
    }



    public function add_to_subject($id){
        $student = $this->studentRepository->getStudentById($id);
        $subjects = $this->subjectRepository->getTeacherSubjects(session('id'));
        return view('teacher.add_to_subject',['student' => $student, 'subjects' => $subjects]);
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
        $students = [];
        $subject = $this->subjectRepository->getSubject($request->subject_id);
        foreach ($selectedStudentIds as $id){
            $students[] = $this->studentRepository->getStudentById($id);
            $inserted_row[] = [
                'student_id' => $id,
                'subject_id' => $request->subject_id,
                'date' => $d,
                'attendance_id' => $attendance_id
            ];
        }
        $this->smsService->NotifyNotComeStudentParents($students, $subject);
        $this->notComeDaysRepository->add($inserted_row);
        return back()->with('success',1);
    }




    public function salaries(){
        return view('teacher.salary',['salaries' => $this->salariesRepository->teacherSalaries(session('id'))]);
    }





    public function payments(){
        $payments = $this->monthlyPaymentRepository->getTeacherPayments100(session('id'));
        return view('teacher.payments',['payments' => $payments]);
    }

    public function payments_by_month(Request $request){
        return $this->monthlyPaymentRepository->getTeacherPaymentsByMonth($request->month, $request->teacher_id);
    }

    public function new_subject(Request $request){
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'lessons_count' => 'required|numeric',
        ]);
        $this->subjectRepository->addSubject($request->name,$request->price,$request->lessons_count,session('id'));
        return back()->with('add',1);
    }



    public function sms_to_group(Request $request){
        $request->validate([
            'subject_id' => 'required|numeric',
            'message' => 'required|string',
        ]);
        $students = $this->attachRepository->getAttachBySubjectId($request->subject_id);
        $res = $this->smsService->sendSmsSubject($students,$request->message);
        if($res['status'] == 'success') return back()->with('success',1);
        else return back()->with('error',1);
    }

    public function debt(Request $request){
        $request->validate([
            'subject_id' => 'required|numeric',
            'message' => 'required|string',
            'month' => 'required|string',
        ]);
        $students = $this->monthlyPaymentRepository->getDebtStudents($request->month, $request->subject_id);
        $res = $this->smsService->sendSmsSubject($students, $request->message);
        if($res['status'] == 'success') return back()->with('success',1);
        else return back()->with('error',1);
    }


}
