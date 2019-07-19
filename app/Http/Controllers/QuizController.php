<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\QuizOption;
use DB;
use Auth;
use App\Sosadfun\Traits\QuizObjectTraits;
use Carbon;

class QuizController extends Controller
{
    use QuizObjectTraits;

    public function __construct()
    {
        $this->middleware('admin')->except('taketest','submittest','quiz_entry');
        $this->middleware('auth')->only('taketest','submittest','quiz_entry');
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
        $level = (int)$request->level ?? 0;
        $quizzes = $this->random_quizzes($level);
        return view('quiz.taketest',compact('level', 'quizzes', 'user'));
    }

    public function quiz_entry()
    {
        $user = Auth::user();
        return view('quiz.quiz_entry', compact('user'));
    }

    public function submittest(Request $request)
    {
        $user = Auth::user();
        $wrong_quiz = [];
        //dd($request->all());
        $this->validate($request, [
            'quiz-answer' => 'required',
        ]);
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
            if($user->quiz_level<=$request->level){
                $user->reward('first_quiz', $request->level+1);
                $user->quiz_level = $request->level+1;
                if($user->level<1){$user->level=1;}
                $user->save();
                return redirect('/')->with('success', '恭喜，初次答对本组题目的奖励已经发放！');
            }else{
                $user->reward('more_quiz');
                return redirect('/')->with('success', '恭喜，已经完成了测试！多次答对题目的奖励已经发放！');
            }
        }else{
            $level = (int)$request->level ?? 0;
            return view('quiz.analyzequiz', compact('wrong_quiz','user','level'));
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
