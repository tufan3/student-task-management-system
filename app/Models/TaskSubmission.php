<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    protected $fillable = [
        'task_id',
        'student_id',
        'note',
        'files',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class);
    }

    public function taskFeedback()
    {
        return $this->hasOne(TaskFeedback::class, 'submission_id');
    }

}
