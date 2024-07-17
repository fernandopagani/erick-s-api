<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtasks extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'task_id',
        'description',
        'status'

    ];



    public function tasks()
    {
        return $this->belongsTo(Tasks::class);
    }
}
