<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'student_id', 'coach_id', 'score', 'feedback', 'message', 'category',
    ];
}
