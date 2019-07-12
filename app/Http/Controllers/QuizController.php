<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizOption;
use DB;
use Auth;
use App\Sosadfun\Traits\QuizObjectTraits;
use Carbon\Carbon;

class QuizController extends Controller
{
    use QuizObjectTraits;

    public function __construct()
    {
        $this->middleware('admin')->except('taketest','submittest');
        $this->middleware('auth')->only('taketest','submittest');
    }

    public function review()
    {
        $quizzes = Quiz::with('quiz_options')
        ->orderBy('created_at','desc')
        ->paginate(config('constants.index_per_page'));
        return view('quiz.review',compact('quizzes'));
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'quiz-body' => 'required|string|max:6000',
            'quiz-hint' => 'nullable|string|max:6000',
            'quiz-level'=> 'required|numeric',
            'quiz-option.*' => 'nullable|string|max:190',
            'quiz-option-explanation.*' => 'nullable|string|max:190',
        ]);
        $quizdata['body'] = $request['quiz-body'];
        $quizdata['quiz_level'] = (int)$request['quiz-level'];
        $quizdata['hint'] = $request['quiz-hint'];
        DB::transaction(function () use ($request, $quizdata){
            $quiz = Quiz::create($quizdata);
            foreach($request['quiz-option'] as $key=>$option){
                if($option){
                    $optiondata['quiz_id'] = $quiz->id;
                    $optiondata['body'] = $option;
                    $optiondata['explanation'] = $request['quiz-option-explanation'][$key];
                    $optiondata['is_correct'] = !empty($request['check-quiz-option'])&&array_key_exists($key, $request['check-quiz-option']);
                    QuizOption::create($optiondata);
                }
            }
        });

        return redirect()->route('quiz.review');
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('quiz_options');
        return view('quiz.edit', compact('quiz'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('quiz_options');
        return view('quiz.show', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $this->validate($request, [
            'quiz-body' => 'required|string|max:6000',
            'quiz-hint' => 'nullable|string|max:6000',
            'quiz-level'=> 'required|numeric',
            'quiz-option.*' => 'nullable|string|max:190',
            'quiz-option-explanation.*' => 'nullable|string|max:190',
        ]);
        $quizdata['body'] = $request['quiz-body'];
        $quizdata['quiz_level'] = (int)$request['quiz-level'];
        $quizdata['hint'] = $request['quiz-hint'];
        DB::transaction(function () use ($request, $quizdata, $quiz){
            $quiz->update($quizdata);
            foreach($request['quiz-option'] as $key=>$option){
                if($option){ // 选项干必须非空白
                    if($key===0){
                        $optiondata['quiz_id'] = $quiz->id;
                        $optiondata['body'] = $option;
                        $optiondata['explanation'] = $request['quiz-option-explanation'][$key];
                        $optiondata['is_correct'] = !empty($request['check-quiz-option'])&&array_key_exists($key, $request['check-quiz-option']);
                        QuizOption::create($optiondata);
                    }else{
                        $quiz_option = QuizOption::findOrfail($key);
                        if($quiz_option&&$quiz_option->quiz_id===$quiz->id){
                            $optiondata['body'] = $option;
                            $optiondata['explanation'] = $request['quiz-option-explanation'][$key];
                            $optiondata['is_correct'] = !empty($request['check-quiz-option'])&&array_key_exists($key, $request['check-quiz-option']);
                            $quiz_option->update($optiondata);
                        }
                    }
                }
            }
        });
        return redirect()->route('quiz.show', $quiz);
    }

    public function taketest(Request $request)
    {
        $user = Auth::user();
        $level = (int)$request->quiz_level ?? 0;
        $quizzes = $this->random_quizzes($level);
        return view('quiz.taketest',compact('level', 'quizzes', 'user'));
    }
    public function submittest(Request $request)
    {
        $user = Auth::user();
        $wrong_quiz = [];
        foreach($request['quiz-answer'] as $quiz_answer){
            $submitted_answers = $this->select_submitted_answers($quiz_answer);
            $correct_answers = $this->find_quiz_answers((int)$quiz_answer['quiz_id']);
            if($submitted_answers!=$correct_answers){
                array_push($wrong_quiz, [
                    'submitted_answers' => $submitted_answers,
                    'correct_answers' => $correct_answers,
                    'quiz' => $this->find_quiz_set((int)$quiz_answer['quiz_id']),
                ]);
            }
        }
        if(empty($wrong_quiz)){
            if(!$user->last_quizzed_at){
                $user->reward('first_quiz');
                $user->last_quizzed_at = Carbon::now();
                $user->save();
            }else{
                $user->reward('more_quiz');
            }
            return redirect('/')->with('success', '恭喜，已经完成了测试！正确答题的奖励已经发放！');
        }else{
            return view('quiz.analyzequiz', compact('wrong_quiz','user'));
        }
    }

    private function find_quiz_answers($quiz_id)
    {
        return $quiz_option = $this->all_quiz_answers()->filter(function($item) use ($quiz_id) {
            return $item->quiz_id == $quiz_id && $item->is_correct;
        })->sortBy('id')->pluck('id')->toArray();
    }

    private function select_submitted_answers($quiz_answer)
    {
        $submitted_answers = [];
        foreach($quiz_answer as $key=>$answer){
            if($key!='quiz_id'){
                array_push($submitted_answers, $key);
            }
        }
        sort($submitted_answers);
        return $submitted_answers;
    }
}
