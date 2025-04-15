<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskFeedback extends Model
{
    protected $fillable = [
        'task_submission_id',
        'teacher_id',
        'feedback',
    ];

    public function taskSubmission()
    {
        return $this->belongsTo(TaskSubmission::class, 'submission_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class);
    }


}
