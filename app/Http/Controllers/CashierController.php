<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AttachRepository;
use App\Repositories\CashierRepository;
use App\Repositories\MonthlyPaymentRepository;
use App\Repositories\OutlayRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function __construct(
        protected TeacherRepository $teacherRepository,
        protected CashierRepository $cashierRepository,
        protected SubjectRepository $subjectRepository,
        protected StudentRepository $studentRepository,
        protected MonthlyPaymentRepository $monthlyPaymentRepository,
        protected AttachRepository $attachRepository,
        protected OutlayRepository $outlayRepository,
    )
    {
    }

    //    Auth
    public function auth(LoginRequest $request){
        $cashier = $this->cashierRepository->getCashier($request->username);
        if (!$cashier){
            return back()->with('login_error', 1);
        }
        if (Hash::check($request->input('password'), $cashier->password)) {
            session()->put('cashier',1);
            session()->put('name',$cashier->name);
            session()->put('id',$cashier->id);
            session()->put('photo',$cashier->photo);
            session()->put('username',$cashier->username);
            return redirect()->route('cashier.home');
        }
        else{
            return back()->with('login_error', 1);
        }
    }

    public function logout(){
        session()->flush();
        return redirect()->route('cashier.login');
    }

    public function profile(){
        $admin = $this->cashierRepository->getCashier(session('username'));
        return view('cashier.profile', ['user' => $admin]);
    }

    public function update(Request $request){
        $request->validate([
            'password1' => 'required|string',
            'password2' => 'required|string',
            'username' => 'required|string',
        ]);
        if ($request->input('password1') != $request->input('password2')) return back()->with('password_error',1);
        $this->cashierRepository->update_password($request->password1, $request->username);
        return back()->with('success',1);
    }

    public function update_avatar(Request $request){
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);
        $cashier = $this->cashierRepository->getCashier(session('username'));
        if ($cashier->photo != 'no_photo.jpg') unlink('img/avatars/'.$cashier->photo);
        $file = $request->file('photo')->extension();
        $name = md5(microtime());
        $photo_name = $name.".".$file;
        session()->put('photo', $photo_name);
        $path = $request->file('photo')->move('img/avatars/',$photo_name);
        $this->cashierRepository->update_photo($photo_name);
        return back()->with('success_photo',1);
    }



    public function home(){
        $payments = $this->monthlyPaymentRepository->getPaymentsByDate(date('Y-m-d'));
        return view('cashier.payment', ['payments' => $payments]);
    }



//    Student control

    public function students(){
        return view('cashier.students', ['students' => $this->studentRepository->getStudents()]);
    }

    public function student($id){
        $student = $this->studentRepository->getStudentWithSubjectsPayments($id);
//        return $student;
        return view('cashier.student', ['student' => $student]);
    }

    public function search(Request $request){
        $students = $this->studentRepository->getStudentsByName($request->name);
        return response()->json($students);
    }

    public function new_student(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|digits:9'
        ]);
        $student = $this->studentRepository->getStudentByName($request->name);
        if (!empty($student)) return back()->with('username_error',1);
        $this->studentRepository->addStudentCashier($request->name, $request->phone);
        return back()->with('success',1);
    }

    public function add_to_subject($id){
        $student = $this->studentRepository->getStudentById($id);
        $subjects = $this->subjectRepository->getAllSubjects();
        return view('cashier.add_to_subject',['student' => $student, 'subjects' => $subjects]);
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
        $attachedSubjectId = $this->attachRepository->addAttach($student->id, $subject->id, $subject->name);
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
                    'amount' => ($countdown == 1) ? $firstMonthPrice : $price,
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
                    'amount' => ($countdown == 1) ? $firstMonthPrice : $price,
                    'month' => Carbon::create($currentYear, $month, 1)->format('Y-m-d'),
                ];
                $rowsToInsert[] = $row;
            }
        }
        $this->monthlyPaymentRepository->add($rowsToInsert);
        return redirect()->route('cashier.student', ['id' => $student->id])->with('attach',1);
    }

    public function getAttachs($student_id){
        return $this->attachRepository->getAttachStudentId($student_id);
    }

//    Not use
    public function payment($id){
        if (!$id) return back();
        $student = $this->studentRepository->getStudentWithSubjects($id);
        return view('cashier.payment',['student' => $student]);
    }





//  Subject control
    public function subjects(){
        $subs = $this->subjectRepository->getAllSubjects();
        $teachers = $this->teacherRepository->getTeachers();
        return view('cashier.subjects', ['subjects' => $subs, 'teachers' => $teachers]);
    }

    public function subject($subject_id){
       return $this->subjectRepository->getSubjectWithTeacher($subject_id);
    }

    public function new_subject(Request $request){
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
            'teacher_id' => 'required|numeric',
            'lessons_count' => 'required|numeric',
        ]);
        $this->subjectRepository->addSubject($request->name,$request->price,$request->lessons_count,$request->teacher_id);
        return back()->with('add',1);
    }

    public function getTeacherWithSubjects($teacher_id){
        return $this->teacherRepository->getTeacherWithSubjects($teacher_id);
    }


//  Payment control
    public function getMonthlyPayments($attach_id){
        return $this->attachRepository->getAttachWithMonthlyPayments($attach_id);
    }

    public function getPayment($payment_id){
        return $this->monthlyPaymentRepository->getPayment($payment_id);
    }

    public function paid(Request $request){
        $request->validate([
            'id' => 'required|numeric',
            'amount' => 'required|numeric',
            'type' => 'required|string',
        ]);
        $payment = $this->monthlyPaymentRepository->getPayment($request->id);
        $student = $this->studentRepository->getStudentById($payment->student_id);
        $subject = $this->subjectRepository->getSubject($payment->subject_id);
        if ($request->amount > $payment->amount) return back()->with('amount_error',1);
        if (!$payment) return back()->with('payment_error',1);
        $status = 0;
        if ($request->has('status')){
            $amount = $request->amount + $payment->amount_paid;
            $this->monthlyPaymentRepository->payment($payment->id, 0, $amount,$request->type, 1);
        }
        elseif ($request->amount == $payment->amount){
            $amount = $request->amount + $payment->amount_paid;
            $this->monthlyPaymentRepository->payment($payment->id, 0, $amount,$request->type, 1);
        }
        else{
            $amount_paid = $request->amount + $payment->amount_paid;
            $amount = $payment->amount - $request->amount;
            $this->monthlyPaymentRepository->payment($payment->id, $amount, $amount_paid,$request->type, 0);
        }
        return redirect()->route('cashier.home');
    }



//    Outlay control
    public function outlays(){
        $outlays = $this->outlayRepository->getOutlaysWithTypes();
        $types = $this->outlayRepository->getOutlayTypes();
        return view('cashier.outlays', ['outlays' => $outlays, 'types' => $types]);
    }

    public function add_outlay_type(Request $request){
        $request->validate([
            'name' => 'required|string'
        ]);
        $o = $this->outlayRepository->getOutlayTypeByName($request->name);
        if ($o) return back()->with('name_error', 1);
        $this->outlayRepository->addType($request->name);
        return back()->with('add',1);
    }

    public function add_outlay(Request $request){
        $request->validate([
            'type_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'description' => 'required|string',
        ]);
        $this->outlayRepository->addOutlay($request->type_id, $request->amount, $request->date, session('id'), $request->description);
        return back()->with('success',1);
    }

    public function get_outlays($type_id){
        return $this->outlayRepository->get_outlays($type_id);
    }
}
