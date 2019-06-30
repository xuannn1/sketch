<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    public function quiz_options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_id')->orderBy('created_at', 'asc');
    }
    public function random_options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_id')->inRandomOrder();
    }
}
