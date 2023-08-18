<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AdminRepository;
use App\Repositories\AttachRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\CashierRepository;
use App\Repositories\MonthlyPaymentRepository;
use App\Repositories\NotComeDaysRepository;
use App\Repositories\OutlayRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(
        protected CashierRepository $cashierRepository,
        protected SubjectRepository $subjectRepository,
        protected AdminRepository $adminRepository,
        protected TeacherRepository $teacherRepository,
        protected MonthlyPaymentRepository $monthlyPaymentRepository,
        protected OutlayRepository $outlayRepository,
        protected StudentRepository $studentRepository,
        protected AttachRepository $attachRepository,
        protected AttendanceRepository $attendanceRepository,
        protected NotComeDaysRepository $notComeDaysRepository,
    )
    {
    }

//    Auth
    public function auth(LoginRequest $request){
        $admin = $this->adminRepository->getAdmin($request->username);
        if (!$admin){
            return back()->with('login_error', 1);
        }
        if (Hash::check($request->input('password'), $admin->password)) {
            session()->put('admin',1);
            session()->put('name',$admin->name);
            session()->put('id',$admin->id);
            session()->put('photo',$admin->photo);
            session()->put('username',$admin->username);
            return redirect()->route('admin.home');
        }
        else{
            return back()->with('login_error', 1);
        }
    }

    public function logout(){
        session()->flush();
        return redirect()->route('admin.login');
    }

    public function home(){
        $payments_arr = $this->monthlyPaymentRepository->monthPaymentsByDateOrderType(date('Y-m-d'));
        $outlay = $this->outlayRepository->getOutlayByDate(date('Y-m-d'));
        $payments = $this->monthlyPaymentRepository->getPayments7();
        $cash = 0;
        $transfer = 0;
        $credit_card = 0;
        if (count($payments_arr) > 0){
            foreach ($payments_arr as $item){
                if ($item->type == 'cash') $cash = $item->total;
                else if ($item->type == 'transfer') $transfer = $item->total;
                else $credit_card = $item->total;
            }
        }
        return view('admin.home', ['payments' => $payments,'outlay' => $outlay,'cash' => $cash, 'credit_card' => $credit_card, 'transfer' => $transfer]);
    }

    public function profile(){
        $admin = $this->adminRepository->getAdmin(session('username'));
        return view('admin.profile', ['user' => $admin]);
    }

    public function update(Request $request){
        $request->validate([
            'password1' => 'required|string',
            'password2' => 'required|string',
        ]);
        if ($request->input('password1') != $request->input('password2')) return back()->with('password_error',1);
        $this->adminRepository->update_password($request->password1);
        return back()->with('success',1);
    }

    public function update_avatar(Request $request){
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);
        $admin = $this->adminRepository->getAdmin(session('username'));
        if ($admin->photo != 'no_photo.jpg') unlink('img/avatars/'.$admin->photo);
        $file = $request->file('photo')->extension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        session()->put('photo', $photo_name);
        $path = $request->file('photo')->move('img/avatars/',$photo_name);
        $this->adminRepository->update_photo($photo_name);
        return back()->with('success_photo',1);
    }




//    Teachers manage

    public function teachers(){
        $teachers = $this->teacherRepository->getTeachers();
        return view('admin.teachers', ['teachers' => $teachers]);
    }

    public function getTeacher($username){
        return $this->teacherRepository->getTeacher($username);
    }

    public function add_teacher(Request $request){
        $request->validate([
            'name' => 'required|string',
            'subject' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'phone' => 'required|numeric|digits:9',
        ]);
        $teacher = $this->teacherRepository->getTeacher($request->username);
        if (!empty($teacher)) return back()->with('username_error',1);
        if ($request->hasFile('photo')){
            $file = $request->file('photo')->extension();
            $name = md5(microtime());
            $photo_name = $name.".".$file;
            $path = $request->file('photo')->move('img/avatars/',$photo_name);
            $this->teacherRepository->addTeacher($request->name,$request->subject, $request->phone, $request->username, $request->password, $photo_name);
            return back()->with('success',1);
        }
        else{
            $this->teacherRepository->addTeacher($request->name,$request->subject, $request->phone, $request->username, $request->password, 'no_photo.jpg');
            return back()->with('success',1);
        }
    }

    public function update_teacher(Request $request){
        $request->validate([
            'password1' => 'required|string',
            'password2' => 'required|string',
        ]);
        if ($request->input('password1') != $request->input('password2')) return back()->with('password_error',1);
        $this->teacherRepository->update_password($request->password1, $request->username);
        return back()->with('change',2);
    }




//    Cashiers control
    public function cashiers(){
        $cashiers = $this->cashierRepository->getCashiers();
        return view('admin.cashiers', ['cashiers' => $cashiers]);
    }

    public function add_cashier(Request $request){
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'phone' => 'required|numeric|digits:9',
        ]);
        $teacher = $this->cashierRepository->getCashier($request->username);
        if (!empty($teacher)) return back()->with('username_error',1);
        if ($request->hasFile('photo')){
            if ($request->file('photo')->getSize() > 2000000) return back()->with('size_error',1);
            $file = $request->file('photo')->extension();
            $name = md5(microtime());
            $photo_name = $name.".".$file;
            $path = $request->file('photo')->move('img/avatars/',$photo_name);
            $this->cashierRepository->addCashier($request->name, $request->phone, $request->username, $request->password, $photo_name);
            return back()->with('success',1);
        }
        else{
            $this->cashierRepository->addCashier($request->name, $request->phone, $request->username, $request->password, 'no_photo.jpg');
            return back()->with('success',1);
        }
    }

    public function update_cashier(Request $request){
        $request->validate([
            'password1' => 'required|string',
            'password2' => 'required|string',
        ]);
        if ($request->input('password1') != $request->input('password2')) return back()->with('password_error',1);
        $this->cashierRepository->update_password($request->password1, $request->username);
        return back()->with('change',2);
    }




//    Payment control
    public function payments(){
        $payments = $this->monthlyPaymentRepository->getPayments();
        return view('admin.payments',['payments' => $payments]);
    }


//    Student control
    public function students(){
        $students = $this->studentRepository->getStudents();
        return view('admin.students',['students' =>$students]);
    }

//    subjects control
    public function subjects(){
        $subs = $this->subjectRepository->getAllSubjects();
        return view('admin.subjects', ['subjects' => $subs]);
    }

    public function subjectStudents($subject_id){
        $attach = $this->attachRepository->getAttachWithStudentsAndTeacher($subject_id);
        if (count($attach) < 1) return back()->with('attach_error',1);
        $payments = $this->monthlyPaymentRepository->monthPaymentsBySubjectId($subject_id);
        $payments_success = $this->monthlyPaymentRepository->getPaidPaymentsByMonth($subject_id);
        return view('admin.subject_students',['subject_id'=>$subject_id,'attachs' => $attach, 'payments' => $payments,'payments_success' => $payments_success]);
    }




    public function attendances(){
        $subjects = $this->subjectRepository->getAllSubjects();
        return view('admin.attendance', ['subjects' => $subjects]);
    }

    public function attendance($subject_id){
        $attendances = $this->attendanceRepository->getAttendanceBySubjectId($subject_id, '09');
        $absentDay = $this->notComeDaysRepository->getTotalAbsentDays($subject_id,'09');
//        return ['attendances' => $attendances, 'attachs' => $attachs, 'subject_id' => $subject_id];
        return view('admin.attendances', ['absentDay'=> $absentDay,'attendances' => $attendances, 'subject_id' => $subject_id]);
    }



    public function sms(){
        $subjects = $this->subjectRepository->getAllSubjects();
        return view('admin.sms',['subjects' => $subjects]);
    }
}
