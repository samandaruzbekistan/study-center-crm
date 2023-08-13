<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AttachRepository;
use App\Repositories\MonthlyPaymentRepository;
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
}
