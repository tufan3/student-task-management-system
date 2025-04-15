<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskSubmission;
use App\Models\TaskFeedback;

class TaskFeedbackController extends Controller
{
    public function store(Request $request, TaskSubmission $submission)
    {
        $request->validate([
            'feedback' => 'required|string|max:255',
        ]);

        $taskFeedback = new TaskFeedback();
        $taskFeedback->submission_id = $submission->id;
        $taskFeedback->teacher_id = auth()->user()->id;
        $taskFeedback->feedback = $request->feedback;
        $taskFeedback->save();

        return redirect()->back()->with('success', 'Feedback submitted successfully');
    }
}
