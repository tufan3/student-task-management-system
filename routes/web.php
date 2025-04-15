<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\loginController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\HeadmasterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\TaskSubmissionController;
use App\Http\Controllers\TaskFeedbackController;
// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::redirect('/', 'login')->name('login');
// Route::get('/', [loginController::class, 'login_view'])->name('login');
Route::post('/', [loginController::class, 'login'])->name('login');

Route::middleware(['auth', 'role:headmaster,teacher'])->group(function () {
    Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
    Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
    Route::get('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');

    Route::get('/headmaster/delete-requests', [HeadmasterController::class, 'deleteRequests'])->name('headmaster.deleteRequests');
    Route::post('/headmaster/delete-requests/{id}/approve', [HeadmasterController::class, 'approve'])->name('headmaster.approve');
    Route::post('/headmaster/delete-requests/{id}/reject', [HeadmasterController::class, 'reject'])->name('headmaster.reject');

    Route::get('/task-approvals', [TaskController::class, 'taskApprovalList'])->name('task.approval.list');
    Route::post('/tasks/{task}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
    Route::post('/tasks/{task}/reject', [TaskController::class, 'reject'])->name('tasks.reject');

    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Route::resource('announcements', AnnouncementController::class);
});

Route::middleware(['auth', 'role:teacher,headmaster,student'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::get('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::post('/tasks/{task}/approve', [TaskController::class, 'approve'])->name('tasks.approve');
    Route::get('/student/tasks', [TaskController::class, 'studentTasks'])->name('student.tasks');

    Route::post('/task-feedbacks/{submission}', [TaskFeedbackController::class, 'store'])->name('task-feedbacks.store');

});

Route::middleware(['auth', 'role:student'])->group(function () {
    // Route::get('my-tasks', [StudentTaskController::class, 'index']);
    Route::post('/task-submissions/{task}', [TaskSubmissionController::class, 'store'])->name('task-submissions.store');
});

Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

Route::get('/password/change', [ProfileController::class, 'passwordChange'])->name('password.change');
Route::post('/password/change', [ProfileController::class, 'passwordUpdate'])->name('password.update');
