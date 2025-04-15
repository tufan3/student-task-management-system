<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskApprovedNotification;
use App\Notifications\TaskCreateNotification;
use App\Notifications\TaskUpdateNotification;
use App\Notifications\TaskDeleteNotification;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Task::with(['student', 'teacher', 'taskSubmissions']);

        if ($user->role == 'teacher') {
            $query->where('teacher_id', $user->id);
        }
        if ($user->role == 'student') {
            $query->where('student_id', $user->id);
        }

        $tasks = $query->latest()->get();

        return view('task.index', compact('tasks'));
    }

    public function create()
    {

        $students = User::where('role', 'student')->where('student_created_by', Auth::user()->id)->get();
        return view('task.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $task = new Task();
        $task->teacher_id = Auth::user()->id;
        $task->student_id = $request->student_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->approved_at = null;
        $task->status = 'pending';
        $task->save();

        $createdBy = Auth::user();
        $student = User::where('id', $request->student_id)->first();
        $createdBy->notify(new TaskCreateNotification($createdBy, $student, $task));

        $headmasters = User::where('role', 'headmaster')->first();
        $headmasters->notify(new TaskCreateNotification($createdBy, $student, $task));

        return redirect()->route('tasks.index')->with('success', 'Task assigned successfully!');
    }

    public function edit(Task $task)
    {
        return view('task.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $task->title = $request->title;
        $task->description = $request->description;
        $task->save();

        $createdBy = Auth::user();
        $student = User::where('id', $task->student_id)->first();
        $createdBy->notify(new TaskUpdateNotification($createdBy, $student, $task));

        $headmasters = User::where('role', 'headmaster')->first();
        $headmasters->notify(new TaskUpdateNotification($createdBy, $student, $task));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        $createdBy = Auth::user();
        $student = User::where('id', $task->student_id)->first();
        $createdBy->notify(new TaskDeleteNotification($createdBy, $student, $task));

        $headmasters = User::where('role', 'headmaster')->first();
        $headmasters->notify(new TaskDeleteNotification($createdBy, $student, $task));

        return redirect()->back()->with('success', 'Task deleted successfully!');
    }


    public function taskApprovalList()
    {
        $tasks = Task::with(['student', 'teacher'])->get();
        return view('task.approvals', compact('tasks'));
    }

    public function approve(Task $task)
    {
        $task->approved_at = now();
        $task->status = 'approved';
        $task->save();

        $task->student->notify(new TaskApprovedNotification($task));


        $teacher = User::where('id', $task->teacher_id)->first();
        $teacher->notify(new TaskApprovedNotification($task));

        $headmasters = User::where('role', 'headmaster')->first();
        $headmasters->notify(new TaskApprovedNotification($task));

        return redirect()->route('task.approval.list')->with('success', 'Task approved successfully!');
    }

    public function reject(Task $task)
    {
        $task->status = 'rejected';
        $task->save();
        return redirect()->route('task.approval.list')->with('success', 'Task rejected successfully!');
    }
}
