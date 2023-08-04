<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\CashierRepository;
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

    public function home(){
        return view('cashier.home');
    }






//    Student control

    public function students(){
        return view('cashier.students', ['students' => $this->studentRepository->getStudents()]);
    }

    public function new(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',

        ]);
        $now = Carbon::now();
        $currentYear = 2023;
        $currentMonth = 9;
        $currentDay = $now->day;
        $price = 300000;
        $daysInMonth = $now->daysInMonth;
        $firstMonthPrice = $price - (($price/$daysInMonth)*($currentDay-1));
        $firstMonthPrice = ceil($firstMonthPrice / 1000) * 1000;
        $now = date('Y-m-d H:i:s');
        $rowsToInsert = [];
        if ($currentMonth > 8){
            for ($month = $currentMonth; $month <= 12; $month++){
                $row = [
                    'user_id' => 'samandar',
                    'oy' => Carbon::create($currentYear, $month, 1)->format('Y-m-d')
                ];

                $rowsToInsert[] = $row;
            }
            $currentYear++;
            for ($month = 1; $month <= 8; $month++){
                $row = [
                    'user_id' => 'samandar',
                    'oy' => Carbon::create($currentYear, $month, 1)->format('Y-m-d')
                ];

                $rowsToInsert[] = $row;
            }
        }
        elseif ($currentMonth <= 8){
            for ($month = $currentMonth; $month <= 8; $month++){
                $row = [
                    'user_id' => 'samandar',
                    'oy' => Carbon::create($currentYear, $month, 1)->format('Y-m-d')
                ];

                $rowsToInsert[] = $row;
            }
        }
        return [$firstMonthPrice,$daysInMonth,$currentMonth,$currentYear, $currentDay, $now,$rowsToInsert];
    }








    public function subjects(){
        $subs = $this->subjectRepository->getAllSubjects();
        $teachers = $this->teacherRepository->getTeachers();
        return view('cashier.subjects', ['subjects' => $subs, 'teachers' => $teachers]);
    }

    public function subject($subject_id){
       return $this->subjectRepository->getSubject($subject_id);
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
}
