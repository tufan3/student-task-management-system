<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskSubmissionController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'note' => 'nullable|string',
            'files' => 'nullable|file|max:2048',
        ]);

        $taskSubmission = new TaskSubmission();
        $taskSubmission->task_id = $task->id;
        $taskSubmission->student_id = auth()->user()->id;
        $taskSubmission->note = $request->note;

        if ($request->hasFile('files')) {
            // $originalPath = $request->file('image')->store('announcements/originals', 'public');

            // $image = Image::make(storage_path("app/public/{$originalPath}"))->encode('webp', 90);
            // $webpPath = 'announcements/webp/' . uniqid() . '.webp';
            // Storage::disk('public')->put($webpPath, $image);
            // $announcement->image = $webpPath;

            $filePath = $request->file('files')->store('task_submissions', 'public');
            $taskSubmission->files = $filePath;

        }

        $taskSubmission->save();

        return redirect()->route('tasks.index')->with('success', 'Task submitted successfully');
    }
}
