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
            return Quiz::withQuizLevel($level)
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


    /**
     * @param int $id The quiz_id
     * @param string $answer The answer user submitted
     * @return bool Whether the answer is correct or not
     */
    public static function is_answer_correct(int $id, string $answer) {
        $quiz = self::find_quiz_set($id);
        $correct_answers = $quiz->quiz_options->where('is_correct',true)->pluck('id')->toArray();
        $quiz->delay_count('quiz_count', 1);
        $user_answers = array_map('intval', explode(',', $answer));
        sort($correct_answers);
        sort($user_answers);
        // 统计每一个选项被选择的次数
        foreach ($user_answers as $user_answer) {
            $option = QuizOption::find($user_answer);
            $option->delay_count('select_count', 1);
        }
        if ($correct_answers == $user_answers) {
            $quiz->delay_count('correct_count', 1);
            return true;
        }
        return false;
    }

}
