<?php

use App\Exports\DebtExport;
use App\Exports\DebtExportTeacher;
use App\Exports\PaymentsExport;
use App\Exports\PaymentsExportTeacher;
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

Route::redirect('/', 'teacher');
Route::view('/access-403', 'access')->name('access');

Route::middleware(['access','admin_blocked'])->group(function () {
    Route::view('/cashier', 'cashier.login')->name('cashier.login');
    Route::post('cashier/auth', [CashierController::class, 'auth'])->name('cashier.auth');

});

Route::get('test', [CashierController::class, 'test']);

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
        Route::get('system-lock', [AdminController::class, 'system_lock'])->name('admin.system.lock');


//        Payment manage
        Route::get('payments', [AdminController::class, 'payments'])->name('admin.payments');
        Route::get('payment-details',[CashierController::class, 'payment_details'])->name('admin.payment.details');

//       Student conrol
        Route::get('students',[AdminController::class,'students'])->name('admin.students');

//        Sms control
        Route::get('sms', [AdminController::class, 'sms'])->name('admin.sms');
        Route::post('teachers-sms',[AdminController::class,'sms_to_teachers'])->name('admin.sms.teachers');
        Route::post('students-sms',[AdminController::class,'sms_to_students'])->name('admin.sms.students');

//        Subject control
        Route::get('subjects',[AdminController::class,'subjects'])->name('admin.subjects');
        Route::get('admin-subject-students/{subject_id?}', [AdminController::class, 'subjectStudents'])->name('admin.subject.students');

//        Attendance control
        Route::get('/attendances',[AdminController::class,'attendances'])->name('admin.attendance.subjects');
        Route::get('/attendance/{subject_id?}',[AdminController::class,'attendance'])->name('admin.attendances');
        Route::get('attendance-detail/{subject_id?}/{month?}',[TeacherController::class, 'attendance_detail'])->name('admin.attendance.detail');
        Route::get('day-detail/{id?}',[TeacherController::class, 'attendance_detail_day'])->name('admin.attendance.day');

        Route::get('admin-salaries',[AdminController::class, 'admin_salaries'])->name('admin.salaries.form');
        Route::post('new-salary',[AdminController::class, 'add_salary'])->name('admin.salary.new');


        Route::get('outlays',[AdminController::class, 'outlays'])->name('admin.outlays');
        Route::get('outlays-filtr/{date?}',[AdminController::class, 'outlays_filtr'])->name('admin.outlay.filtr');


        Route::get('admin_outlay',[AdminController::class, 'admin_outlay'])->name('admin.outlay');
        Route::post('admin_outlay-new',[AdminController::class, 'admin_new_outlay'])->name('admin.new.outlay');
        Route::get('admin_outlay-delete/{id?}',[AdminController::class, 'admin_delete_outlay'])->name('admin.delete.outlay');

        Route::get('salaries',[AdminController::class, 'salaries'])->name('admin.salaries');
//        Route::post('new-salary',[AdminController::class, 'add_salary'])->name('admin.salary.new');
    });
});


Route::view('/teacher', 'teacher.login')->name('teacher.login');
Route::post('teacher/auth', [TeacherController::class, 'auth'])->name('teacher.auth');
Route::middleware(['teacher_auth'])->group(function () {
    Route::prefix('teacher')->group(function () {
        Route::get('home', [TeacherController::class, 'home'])->name('teacher.home');
        Route::get('dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
        Route::get('logout', [TeacherController::class, 'logout'])->name('teacher.logout');
        Route::get('profile', [TeacherController::class, 'profile'])->name('teacher.profile');
        Route::post('update',[TeacherController::class,'update'])->name('teacher.update');
        Route::post('update-avatar',[TeacherController::class,'update_avatar'])->name('teacher.avatar');

        Route::get('student-profile/{id?}', [TeacherController::class, 'student'])->name('teacher.student');
        Route::get('students', [TeacherController::class, 'students'])->name('teacher.students');
        Route::get('student-add-to-subject/{student_id?}', [TeacherController::class, 'add_to_subject'])->name('teacher.add_to_subject');
        Route::post('attach-to-group', [TeacherController::class, 'attach'])->name('teacher.attach');
        Route::post('move-student', [TeacherController::class, 'move'])->name('teacher.move.student');
        Route::post('student-deactivate', [TeacherController::class, 'deActiveAttach'])->name('teacher.student.deActiveAttach');
        Route::get('student-check/{id?}/{date?}/{subject_id?}', [TeacherController::class, 'check'])->name('teacher.student.check');

        Route::get('payments',[TeacherController::class,'payments'])->name('teacher.payments');
        Route::get('payments-month',[TeacherController::class,'payments_by_month'])->name('teacher.payment.month');

        Route::post('groups-add', [TeacherController::class, 'new_subject'])->name('teacher.new.subject');

        Route::get('group/{id?}', [TeacherController::class, 'subject_detail'])->name('teacher.subject.detail');        Route::get('payment-details',[CashierController::class, 'payment_details'])->name('cashier.payment.details');
        Route::get('payment-details',[TeacherController::class, 'payment_details'])->name('teacher.payment.details');
        Route::get('attendances/{subject_id?}',[TeacherController::class, 'attendances'])->name('teacher.attendances');
        Route::get('attendance',[TeacherController::class, 'attendance'])->name('teacher.attendance');
        Route::get('attendance-detail/{subject_id?}/{month?}',[TeacherController::class, 'attendance_detail'])->name('teacher.attendance.detail');
        Route::get('day-detail/{id?}',[TeacherController::class, 'attendance_detail_day'])->name('teacher.attendance.day');
        Route::post('attendances-check',[TeacherController::class, 'attendance_check'])->name('teacher.attendances.check');

        Route::get('salaries',[TeacherController::class, 'salaries'])->name('teacher.salaries');
    });
});


Route::prefix('cashier')->group(function () {
    Route::middleware(['cashier_auth','access', 'admin_blocked'])->group(function () {
        Route::get('home', [CashierController::class, 'home'])->name('cashier.home');
        Route::get('payment_home', [CashierController::class, 'payment_home'])->name('cashier.payment_home');
        Route::get('logout', [CashierController::class, 'logout'])->name('cashier.logout');
        Route::get('profile', [CashierController::class, 'profile'])->name('cashier.profile');
        Route::post('update',[CashierController::class,'update'])->name('cashier.update');
        Route::post('update-avatar',[CashierController::class,'update_avatar'])->name('cashier.avatar');

//        Student control
        Route::get('student-profile/{id?}', [CashierController::class, 'student'])->name('cashier.student');
        Route::get('students', [CashierController::class, 'students'])->name('cashier.students');
        Route::post('student-deactivate', [CashierController::class, 'deActiveAttach'])->name('cashier.student.deActiveAttach');
        Route::get('student-check/{id?}/{date?}/{subject_id?}', [CashierController::class, 'check'])->name('cashier.student.check');
        Route::get('student-add-to-subject/{student_id?}', [CashierController::class, 'add_to_subject'])->name('cashier.add_to_subject');
        Route::get('student-delete-in-subject', [CashierController::class, 'delete_student_group'])->name('student.delete.group');


//        Subject control
        Route::get('subjects', [CashierController::class, 'subjects'])->name('cashier.subjects');
        Route::get('cashier-subject-students/{subject_id?}', [CashierController::class, 'subjectStudents'])->name('cashier.subject.students');
        Route::post('groups-add', [CashierController::class, 'new_subject'])->name('cashier.new.subject');
        Route::post('groups-edit', [CashierController::class, 'edit_subject'])->name('cashier.edit.subject');
        Route::get('teacher-groups/{teacher_id?}', [CashierController::class, 'getTeacherWithSubjects'])->name('cashier.teacher.subjects');
        Route::get('group-delete/{id?}', [CashierController::class, 'delete_group'])->name('group.delete');


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

        Route::get('salaries',[CashierController::class, 'salaries'])->name('cashier.salaries');
        Route::post('new-salary',[CashierController::class, 'add_salary'])->name('cashier.salary.new');

//        Sms xizmati
        Route::get('sms', [CashierController::class, 'sms'])->name('cashier.sms');
        Route::post('student-sms', [CashierController::class, 'sendSmsStudent'])->name('cashier.sms.student');
        Route::post('subject', [CashierController::class, 'subject'])->name('cashier.sms.subject');

//        Attendance control
        Route::get('/attendances',[CashierController::class,'attendances'])->name('cashier.attendance.subjects');
        Route::get('/attendance/{subject_id?}',[CashierController::class,'attendance'])->name('cashier.attendances');
        Route::get('attendance-detail/{subject_id?}/{month?}',[TeacherController::class, 'attendance_detail'])->name('cashier.attendance.detail');
        Route::get('day-detail/{id?}',[TeacherController::class, 'attendance_detail_day'])->name('cashier.attendance.day');

    });
});

Route::middleware(['combined_auth'])->group(function () {
//    Student control
    Route::get('search', [CashierController::class, 'search'])->name('cashier.search');
    Route::post('student-add', [CashierController::class, 'new_student'])->name('cashier.new.student');
    Route::post('student-add-teacher', [TeacherController::class, 'new_student'])->name('teacher.new.student');


    Route::post('sms-send-group', [TeacherController::class, 'sms_to_group'])->name('sms.subject');
    Route::post('sms-send-parents', [AdminController::class, 'sms_to_parents'])->name('sms.parents');
    Route::post('smsBySubject', [AdminController::class, 'smsBySubject'])->name('smsBySubject');
    Route::post('debt', [TeacherController::class, 'debt'])->name('cashier.sms.debt');

    Route::get('/export/payments', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(new PaymentsExport, 'payments.xlsx');
    })->name('export.payments');
    Route::get('/export/payments/debt', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(new DebtExport, 'debt.xlsx');
    })->name('export.payments.debt');
    Route::view('/export/payment-filter', 'admin.filter')->name('export.filter');
    Route::view('/export-cashier/payment-filter', 'cashier.filter')->name('export.filter.cashier');
    Route::view('/export-teacher/teacher-filter', 'teacher.filter')->name('export.filter.teacher');
    Route::get('/payment-filter-view', [AdminController::class, 'filter'])->name('filter.view');
    Route::get('/payment-filter-teacher', [AdminController::class, 'filter_teacher'])->name('filter.view.teacher');
    Route::get('/payment-filter-cashier', [AdminController::class, 'filter_cashier'])->name('filter.view.cashier');
    Route::post('/payment-excel', [AdminController::class, 'payment_filter'])->name('filter.excel');
    Route::post('/payment-excel-teacher', [AdminController::class, 'payment_filter_teacher'])->name('filter.excel.teacher');
    Route::post('/payment-outlay', [AdminController::class, 'outlay_filter'])->name('filter.outlay');
    Route::post('/payment-salary', [AdminController::class, 'salary_filter'])->name('filter.salary');

    Route::get('/export/payments/teacher', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(new PaymentsExportTeacher, 'payments.xlsx');
    })->name('export.payments.teacher');
    Route::get('/export/teacher/debt', function () {
        return \Maatwebsite\Excel\Facades\Excel::download(new DebtExportTeacher, 'debt.xlsx');
    })->name('export.teacher.debt');

    Route::get('statistics-debt', [CashierController::class, 'statistics_debt'])->name('cashier.statistics.debt');


    Route::get('subject/{subject_id?}', [CashierController::class, 'subject'])->name('cashier.subject');


//        Region control
    Route::get('districts/{region_id?}', [CashierController::class,'districts'])->name('cashier.district.regionID');
    Route::get('quarters/{district_id?}', [CashierController::class,'quarters'])->name('cashier.quarter.districtID');
});















