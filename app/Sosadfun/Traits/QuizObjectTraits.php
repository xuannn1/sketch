<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;

trait QuizObjectTraits{

    public static function random_quizzes($level=0)
    {
        return Cache::remember('random_quizzes'.$level, 10, function () use ($level) {
            return Quiz::with('random_options')->where('quiz_level','=',$level)->inRandomOrder()->take(config('constants.quiz_test_number'))->get();
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
