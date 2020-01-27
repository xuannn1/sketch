<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use App\Models\Quiz;
use App\Models\QuizOption;

trait QuizObjectTraits{

    public static function random_quizzes($level=-1, $quizType='', $number=5)
    {
        return Cache::remember('random_quizzes'.'|level:'.$level.'|type:'.$quizType.'|number:'.$number, 3, function () use ($level, $quizType, $number) {
            return Quiz::with('random_options')
            ->withQuizLevel($level)
            ->withQuizType($quizType)
            ->isOnline()
            ->inRandomOrder()
            ->take($number)->get();
        });
    }

    public static function all_quiz_answers()
    {
        return Cache::remember('all_quiz_answers', 20, function() {
            return DB::table('quiz_options')->select('id', 'quiz_id', 'is_correct')->get();
        });
    }

    public static function find_quiz_set($quiz_id)
    {
        return Cache::remember('quiz-'.$quiz_id, 20, function() use($quiz_id) {
            $quiz = Quiz::with('quiz_options')->find($quiz_id);
            return $quiz;
        });
    }

}
