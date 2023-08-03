<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\CashierRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function __construct(protected TeacherRepository $teacherRepository, protected CashierRepository $cashierRepository, protected SubjectRepository $subjectRepository)
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

    public function home(){
        return view('cashier.home');
    }

    public function groups(){
        $subs = $this->subjectRepository->getAllSubjects();
        $teachers = $this->teacherRepository->getTeachers();
        return view('cashier.subjects', ['subjects' => $subs, 'teachers' => $teachers]);
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
}
