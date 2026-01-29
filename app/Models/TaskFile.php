<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task;


class TaskFile extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
        'file_name',
        'file_path',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
