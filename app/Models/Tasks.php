<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tasks extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',

        'description',
        'due_date',
        'status'

    ];

    public function subtasks()
    {
        return $this->hasMany(Subtasks::class, 'task_id');
        
    }


    
}
