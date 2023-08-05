<?php

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
    });
});

Route::prefix('cashier')->group(function () {
    Route::post('/auth', [CashierController::class, 'auth'])->name('cashier.auth');
    Route::middleware(['cashier_auth'])->group(function () {
        Route::get('home', [CashierController::class, 'home'])->name('cashier.home');

//        Student control
        Route::get('new', [CashierController::class, 'new'])->name('cashier.new');
        Route::get('students', [CashierController::class, 'students'])->name('cashier.students');
        Route::get('search', [CashierController::class, 'search'])->name('cashier.search');
        Route::post('student-add', [CashierController::class, 'new_student'])->name('cashier.new.student');


//        Subject control
        Route::get('subjects', [CashierController::class, 'subjects'])->name('cashier.subjects');
        Route::get('subject/{subject_id?}', [CashierController::class, 'subject'])->name('cashier.subject');
        Route::post('groups-add', [CashierController::class, 'new_subject'])->name('cashier.new.subject');
        Route::get('teacher-groups/{teacher_id?}', [CashierController::class, 'getTeacherWithSubjects'])->name('cashier.teacher.subjects');
    });
});















Route::get('/', function () {
    return view('welcome');
});
