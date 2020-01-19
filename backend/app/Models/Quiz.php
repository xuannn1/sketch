<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use Traits\DelayCountTrait;
    
    protected $guarded = [];

    public function quiz_options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_id')->orderBy('created_at', 'asc');
    }
    public function random_options()
    {
        return $this->hasMany(QuizOption::class, 'quiz_id')->inRandomOrder();
    }
    public function scopeWithQuizType($query, $type = '')
    {
        if(array_key_exists($type, config('constants.quiz_types'))){
            return $query->where('type',$type);
        }
        if($type==='off_line'){
            return $query->where('is_online', 0);
        }
        return $query;
    }
    public function scopeIsOnline($query)
    {
        return $query->where('is_online', 1);
    }

    public function scopeWithQuizLevel($query, $level)
    {
        if(is_numeric($level)&&$level>=0){
            return $query->where('quiz_level',$level);
        }
        return $query;
    }
}
