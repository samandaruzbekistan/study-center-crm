<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AttachRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\CashierRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\MonthlyPaymentRepository;
use App\Repositories\NotComeDaysRepository;
use App\Repositories\OutlayRepository;
use App\Repositories\SalariesRepository;
use App\Repositories\StudentRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use App\Services\SmsService;
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
        protected SmsService $smsService,
        protected NotComeDaysRepository $notComeDaysRepository,
        protected AttendanceRepository $attendanceRepository,
        protected DistrictRepository $districtRepository,
        protected SalariesRepository $salariesRepository,
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
            session()->flush();
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
        return view('cashier.home', ['payments' => $payments,'outlay' => $outlay,'cash' => $cash, 'credit_card' => $credit_card, 'transfer' => $transfer]);
    }

    public function payment_home(){
        $payments = $this->monthlyPaymentRepository->getPaymentsByDate(date('Y-m-d'));
        return view('cashier.payment', ['payments' => $payments]);
    }



//    Student control

    public function students(){
        return view('cashier.students', ['students' => $this->studentRepository->getStudents()]);
    }

    public function student($id){
        $student = $this->studentRepository->getStudentWithSubjectsPayments($id);
        return view('cashier.student', ['student' => $student]);
    }

    public function search(Request $request){
        $students = $this->studentRepository->getStudentsByName($request->name);
        return response()->json($students);
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
        $student_id = $this->studentRepository->addStudentCashier($request->name, $request->phone,$request->phone2,$request->region_id, $request->district_id,$request->quarter_id);
        return redirect()->route('cashier.student', ['id' => $student_id])->with('success',1);
    }

    public function check($id, $date,$subject_id){
        $attach = $this->attachRepository->getAttach($id, $subject_id);
        $newDate = Carbon::createFromFormat('Y-m-d', $date)
            ->startOfMonth()
            ->format('Y-m-d');
        $carbonDate = Carbon::parse($date);
        $payment = $this->monthlyPaymentRepository->getPaymentByMonth($id, $newDate,$subject_id);
        if (!$payment) return 'month_error';
        if ($payment->status == 0){
            if ($carbonDate->day > 6){
                return 'payment_error';
            }
            else{
                return 'true';
            }
        }
        return 'true';
    }

    public function deActiveAttach(Request $request){
//        return $request;
        $attach = $this->attachRepository->getAttach($request->student_id, $request->subject_id);
        $newDate = Carbon::createFromFormat('Y-m-d', $request->date)
            ->startOfMonth()
            ->format('Y-m-d');
        $carbonDate = Carbon::parse($request->date);
        $payment = $this->monthlyPaymentRepository->getPaymentByMonth($request->student_id, $newDate,$request->subject_id);
        if (($payment->status == 0) and ($carbonDate->day < 6)){
            $this->monthlyPaymentRepository->deleteNowAndNextPayments($newDate, $attach->id);
            $this->attachRepository->deActiveAttach($attach->id);
            return back()->with('deActivated',1);
        }
        if ($payment->status == 1){
            $this->monthlyPaymentRepository->deleteNextPayments($newDate, $attach->id);
            $this->attachRepository->deActiveAttach($attach->id);
            return back()->with('deActivated',1);
        }
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
        $attachedSubjectId = $this->attachRepository->addAttach($student->id, $subject->id, $subject->name, $request->date);
        $carbonDate = Carbon::parse($request->date);
        $currentYear = $carbonDate->year;
        $currentMonth = $carbonDate->month;
        $price = $request->amount;
        $daysInMonth = $carbonDate->daysInMonth;
//        $firstMonthPrice = ceil(($price - (($price / $daysInMonth) * ($carbonDate->day - 1))) / 1000) * 1000;
        $rowsToInsert = [];
        if ($currentMonth > 8){
//            $countdown = 0;
            for ($month = $currentMonth; $month <= 12; $month++){
//                $countdown++;
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
//                $countdown++;
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
        return redirect()->route('cashier.student', ['id' => $student->id])->with('attach',1);
    }

    public function getAttachs($student_id){
        return $this->attachRepository->getAttachStudentId($student_id);
    }

    public function sendSmsStudent(Request $request){
        $request->validate([
            'number' => 'required|numeric|digits:12',
            'message' => 'required|string'
        ]);
        $result = $this->smsService->sendStudent($request->number, $request->message);
        $jsonEncoded = json_decode($result);
        if ($jsonEncoded->status != "waiting") return back()->with('sms_error', 1);
        return back()->with('sms_send',1);
    }

//    Not use
    public function payment($id){
        if (!$id) return back();
        $student = $this->studentRepository->getStudentWithSubjects($id);
        return view('cashier.payment',['student' => $student]);
    }

    public function payments(){
        $payments = $this->monthlyPaymentRepository->getPayments();
        return view('cashier.payments',['payments' => $payments]);
    }



    public function salaries(){
        return view('cashier.salary',['salaries' => $this->salariesRepository->getSalaries(), 'teachers' => $this->teacherRepository->getTeachers()]);
    }

    public function add_salary(Request $request){
        $this->salariesRepository->add($request->teacher_id, $request->month, $request->amount, $request->date, $request->description, session('id'));
        return back()->with('success',1);
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

    public function subjectStudents($subject_id){
        $attach = $this->attachRepository->getAttachWithStudentsAndTeacher($subject_id);
        if (count($attach) < 1) return back()->with('attach_error',1);
        $payments = $this->monthlyPaymentRepository->monthPaymentsBySubjectId($subject_id);
        $payments_success = $this->monthlyPaymentRepository->getPaidPaymentsByMonth($subject_id);
        return view('cashier.subject_students',['subject_id'=>$subject_id,'attachs' => $attach, 'payments' => $payments,'payments_success' => $payments_success]);
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

    public function payment_details(Request $request){
        return $this->monthlyPaymentRepository->getPaymentsByMonth($request->month, $request->subject_id);
    }

    public function payment_filtr($date){
        if (!$date) return 'date not detected';
        $summa = $this->monthlyPaymentRepository->filtr_summa($date);
        return [$this->monthlyPaymentRepository->filtr($date), $summa];
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
        if ($request->has('status')){
            $amount = $request->amount + $payment->amount_paid;
            $this->monthlyPaymentRepository->payment($payment->id, 0, $amount,$request->type, 1);
        }
        elseif ($request->amount == $payment->amount){
            $amount = $request->amount + $payment->amount_paid;
            $this->monthlyPaymentRepository->payment($payment->id, 0, $amount,$request->type, 1);
        }
        else{
            $this->monthlyPaymentRepository->addPayment($payment->attach_id, $payment->student_id,$payment->subject_id, $payment->teacher_id,0,$payment->month, $request->amount, $request->type);
            $amount = $payment->amount - $request->amount;
            $this->monthlyPaymentRepository->updatePayment($payment->id, $amount);
//            $amount_paid = $request->amount + $payment->amount_paid;
//            $amount = $payment->amount - $request->amount;
//            $this->monthlyPaymentRepository->payment($payment->id, $amount, $amount_paid,$request->type, 0);
        }
        return redirect()->route('cashier.home')->with('success',1);;
    }

    public function month_payment($subject_id){
        $payments = $this->monthlyPaymentRepository->monthPaymentsBySubjectId($subject_id);
        return $payments;
    }

    public function statistics_debt(){
        $teachers = $this->teacherRepository->getTeachers();
        return view('cashier.debt', ['teachers' => $teachers]);
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



//    SMS control


    public function sms(){
        $subjects = $this->subjectRepository->getAllSubjects();
        return view('cashier.sms',['subjects' => $subjects]);
    }


//    Attendance control
    public function attendances(){
        $subjects = $this->subjectRepository->getAllSubjects();
        return view('cashier.attendance', ['subjects' => $subjects]);
    }

    public function attendance($subject_id){
        $attendances = $this->attendanceRepository->getAttendanceBySubjectId($subject_id, '09');
        $absentDay = $this->notComeDaysRepository->getTotalAbsentDays($subject_id,'09');
//        return ['attendances' => $attendances, 'attachs' => $attachs, 'subject_id' => $subject_id];
        return view('cashier.attendances', ['absentDay'=> $absentDay,'attendances' => $attendances, 'subject_id' => $subject_id]);
    }

    //    Region control
    public function districts($region_id){
        return $this->districtRepository->districts($region_id);
    }

    public function quarters($district_id){
        return $this->districtRepository->quarters($district_id);
    }
}
