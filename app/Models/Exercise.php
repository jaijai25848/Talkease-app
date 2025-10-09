<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'level', 'category', 'word', 'text', 'content', 'title',
    ];
}
