<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/admin', 'admin.login')->name('admin.login');
Route::view('/teacher', 'teacher.login')->name('teacher.login');
Route::view('/cashier', 'cashier.login')->name('cashier.login');
Route::redirect('/','teacher/login');

Route::prefix('admin')->group(function () {
    Route::post('/auth', [AdminController::class, 'auth'])->name('admin.auth');
    Route::middleware(['admin_auth'])->group(function () {
        Route::get('home', [AdminController::class, 'home'])->name('admin.home');
        Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
        Route::get('profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('update',[AdminController::class,'update'])->name('admin.update');
        Route::post('update-avatar',[AdminController::class,'update_avatar'])->name('admin.avatar');

//        Teachers manage
        Route::get('teachers',[AdminController::class, 'teachers'])->name('admin.teachers');
        Route::get('teacher/{username?}',[AdminController::class, 'getTeacher'])->name('admin.get.teacher');
        Route::post('teacher-add',[AdminController::class, 'add_teacher'])->name('admin.new.teacher');
        Route::post('update-teacher',[AdminController::class, 'update_teacher'])->name('admin.update.teacher');

//        Cashiers manage
        Route::get('cashiers',[AdminController::class, 'cashiers'])->name('admin.cashiers');
        Route::post('cashier-add',[AdminController::class, 'add_cashier'])->name('admin.new.cashier');
        Route::post('update-cashier',[AdminController::class, 'update_cashier'])->name('admin.update.cashier');


//        Payment manage
        Route::get('payments', [AdminController::class, 'payments'])->name('admin.payments');
    });
});

Route::prefix('teacher')->group(function () {
    Route::post('/auth', [TeacherController::class, 'auth'])->name('teacher.auth');
    Route::middleware(['teacher_auth'])->group(function () {
        Route::get('home', [TeacherController::class, 'home'])->name('teacher.home');
        Route::get('group/{id?}', [TeacherController::class, 'subject_detail'])->name('teacher.subject.detail');        Route::get('payment-details',[CashierController::class, 'payment_details'])->name('cashier.payment.details');
        Route::get('payment-details',[TeacherController::class, 'payment_details'])->name('teacher.payment.details');
        Route::get('attendances/{subject_id?}',[TeacherController::class, 'attendances'])->name('teacher.attendances');
        Route::post('attendances-check',[TeacherController::class, 'attendance_check'])->name('teacher.attendances.check');

    });
});


Route::prefix('cashier')->group(function () {
    Route::post('/auth', [CashierController::class, 'auth'])->name('cashier.auth');
    Route::middleware(['cashier_auth'])->group(function () {
        Route::get('home', [CashierController::class, 'home'])->name('cashier.home');
        Route::get('logout', [CashierController::class, 'logout'])->name('cashier.logout');
        Route::get('profile', [CashierController::class, 'profile'])->name('cashier.profile');
        Route::post('update',[CashierController::class,'update'])->name('cashier.update');
        Route::post('update-avatar',[CashierController::class,'update_avatar'])->name('cashier.avatar');

//        Student control
        Route::get('new', [CashierController::class, 'new'])->name('cashier.new');
        Route::get('student-profile/{id?}', [CashierController::class, 'student'])->name('cashier.student');
        Route::get('students', [CashierController::class, 'students'])->name('cashier.students');
        Route::get('search', [CashierController::class, 'search'])->name('cashier.search');
        Route::post('student-add', [CashierController::class, 'new_student'])->name('cashier.new.student');
        Route::get('student-add-to-subject/{student_id?}', [CashierController::class, 'add_to_subject'])->name('cashier.add_to_subject');


//        Subject control
        Route::get('subjects', [CashierController::class, 'subjects'])->name('cashier.subjects');
        Route::get('cashier-subject-students/{subject_id?}', [CashierController::class, 'subjectStudents'])->name('cashier.subject.students');
        Route::get('subject/{subject_id?}', [CashierController::class, 'subject'])->name('cashier.subject');
        Route::post('groups-add', [CashierController::class, 'new_subject'])->name('cashier.new.subject');
        Route::get('teacher-groups/{teacher_id?}', [CashierController::class, 'getTeacherWithSubjects'])->name('cashier.teacher.subjects');


//        Monthly payments control
        Route::get('all-payments',[CashierController::class, 'payments'])->name('cashier.payments.all');
        Route::get('monthly-payments/{attach_id?}', [CashierController::class, 'getMonthlyPayments'])->name('cashier.payments');
        Route::get('monthly-payment/{payment_id?}', [CashierController::class, 'getPayment'])->name('cashier.getPayment');
        Route::post('paid', [CashierController::class, 'paid'])->name('cashier.paid');
        Route::get('month-payment/{subject_id}',[CashierController::class, 'month_payment'])->name('cashier.month.payments');
        Route::get('payment-details',[CashierController::class, 'payment_details'])->name('cashier.payment.details');
        Route::get('payment-filtr/{date?}',[CashierController::class, 'payment_filtr'])->name('cashier.payment.filtr');


//        Attach control
        Route::post('attach-to-group', [CashierController::class, 'attach'])->name('cashier.attach');
        Route::get('getAttachs/{student_id?}', [CashierController::class, 'getAttachs'])->name('cashier.getAttachs');


//        Outlay control
        Route::get('outlays',[CashierController::class, 'outlays'])->name('cashier.outlays');
        Route::post('new-outlay-type',[CashierController::class, 'add_outlay_type'])->name('cashier.outlay.new.type');
        Route::post('new-outlay',[CashierController::class, 'add_outlay'])->name('cashier.outlay.new');
        Route::get('get-outlays/{type_id?}',[CashierController::class, 'get_outlays'])->name('cashier.outlays.get');


//        Sms xizmati
        Route::get('sms', [CashierController::class, 'sms'])->name('cashier.sms');
        Route::post('student-sms', [CashierController::class, 'sendSmsStudent'])->name('cashier.sms.student');
        Route::post('debt', [CashierController::class, 'debt'])->name('cashier.sms.debt');
        Route::post('subject', [CashierController::class, 'subject'])->name('cashier.sms.subject');
    });
});













