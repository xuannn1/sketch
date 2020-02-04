<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $this->middleware('auth:api');
        $this->middleware('admin')->only('index','store','update','destroy','show');

    }

    public function index(Request $request)
    {
        // $quizzes = Quiz::with('quiz_options')
        // ->withQuizType($request->quizType)
        // ->withQuizLevel($request->quizLevel)
        // ->orderBy('id','desc')
        // ->paginate(config('constants.index_per_page'))
        // ->appends($request->only('quizType','quizLevel'));
        // return view('quiz.review',compact('quizzes'))->with('quizType',$request->quizType)->with('quizLevel',$request->quizLevel);
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'quiz-body' => 'required|string|max:6000',
        //     'quiz-hint' => 'nullable|string|max:6000',
        //     'quiz-level'=> 'required|numeric',
        //     'quiz-option.*' => 'nullable|string|max:190',
        //     'quiz-type' => 'required|string',
        //     'quiz-option-explanation.*' => 'nullable|string|max:190',
        // ]);
        // $quizdata['body'] = $request['quiz-body'];
        // $quizdata['quiz_level'] = (int)$request['quiz-level'];
        // $quizdata['hint'] = $request['quiz-hint'];
        // $quizdata['type'] = $request['quiz-type'];
        // if(!array_key_exists($request['quiz-type'], config('constants.quiz_types'))){abort(422);}
        // DB::transaction(function () use ($request, $quizdata){
        //     $quiz = Quiz::create($quizdata);
        //     foreach($request['quiz-option'] as $key=>$option){
        //         if($option){
        //             $optiondata['quiz_id'] = $quiz->id;
        //             $optiondata['body'] = $option;
        //             $optiondata['explanation'] = $request['quiz-option-explanation'][$key];
        //             $optiondata['is_correct'] = !empty($request['check-quiz-option'])&&array_key_exists($key, $request['check-quiz-option']);
        //             QuizOption::create($optiondata);
        //         }
        //     }
        // });
        //
        // return redirect()->route('quiz.review');
    }

    public function show($id)
    {
        // $quiz = Quiz::on('mysql::write')->find($id);
        // $quiz->load('quiz_options');
        // return view('quiz.show', compact('quiz'));
    }

    public function update($id, Request $request)
    {
        // $quiz = Quiz::on('mysql::write')->find($id);
        //
        // $this->validate($request, [
        //     'quiz-body' => 'required|string|max:6000',
        //     'quiz-hint' => 'nullable|string|max:6000',
        //     'quiz-level'=> 'required|numeric',
        //     'quiz-type' => 'required|string',
        //     'quiz-option.*' => 'nullable|string|max:190',
        //     'quiz-option-explanation.*' => 'nullable|string|max:190',
        // ]);
        // $quizdata['body'] = $request['quiz-body'];
        // $quizdata['quiz_level'] = (int)$request['quiz-level'];
        // $quizdata['hint'] = $request['quiz-hint'];
        // $quizdata['type'] = $request['quiz-type'];
        // $quizdata['is_online'] = $request['quiz-is-online']?true:false;
        // if(!array_key_exists($request['quiz-type'], config('constants.quiz_types'))){abort(422);}
        // DB::transaction(function () use ($request, $quizdata, $quiz){
        //     $quiz->update($quizdata);
        //     foreach($request['quiz-option'] as $key=>$option){
        //         if($option){ // 选项干必须非空白
        //             if($key===0){
        //                 $optiondata['quiz_id'] = $quiz->id;
        //                 $optiondata['body'] = $option;
        //                 $optiondata['explanation'] = $request['quiz-option-explanation'][$key];
        //                 $optiondata['is_correct'] = !empty($request['check-quiz-option'])&&array_key_exists($key, $request['check-quiz-option']);
        //                 QuizOption::create($optiondata);
        //             }else{
        //                 $quiz_option = QuizOption::findOrfail($key);
        //                 if($quiz_option&&$quiz_option->quiz_id===$quiz->id){
        //                     $optiondata['body'] = $option;
        //                     $optiondata['explanation'] = $request['quiz-option-explanation'][$key];
        //                     $optiondata['is_correct'] = !empty($request['check-quiz-option'])&&array_key_exists($key, $request['check-quiz-option']);
        //                     $quiz_option->update($optiondata);
        //                 }
        //             }
        //         }
        //     }
        // });
        // return redirect()->route('quiz.show', $quiz);
    }

    public function getQuiz(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            abort(401,'用户未登录。');
        }
        $level = (int)$request->level ?? 0;
        $quizzes = $this->random_quizzes($level, 'level_up', config('constants.quiz_test_number'));
        $quiz_questions = implode(",", $quizzes->pluck('id')->toArray());
        if (!$quizzes || empty($quizzes) || count($quizzes) == 0) {
            abort(404,'没有找到该等级的题目。');
        }
        UserInfo::find($user->id)->update(['quiz_questions' => $quiz_questions]);
        return response()->success(['quizzes' => QuizResource::collection($quizzes)]);
    }

    public function submitQuiz(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            abort(401,'用户未登录。');
        }
        $user_info = UserInfo::find($user->id);
        $quiz = $request->quizzes;
        $result = [
            'id' => $user->id,
            'type' => 'quiz_result',
            'attribute' => [
                'is_passed' => false,
                'is_quiz_level_up' => false
            ]
        ];
        // 设置应答对题目数量为总题目数量
        $is_passed = $this->check_quiz_passed_or_not($quiz, $user_info->quiz_questions, config('constants.quiz_test_number'));
        $current_quiz_level = self::find_quiz_set($quiz[0]['id'])->quiz_level;
        $result['attribute']['current_quiz_level'] = $current_quiz_level;
        if($is_passed){
            $result['attribute']['is_passed'] = true;
            if($user->quiz_level<=$current_quiz_level){
                $user->reward('first_quiz', $current_quiz_level+1);
                $user->quiz_level = $current_quiz_level+1;
                if($user->level<1){$user->level=1;}
                $user->save();
                $result['attribute']['is_quiz_level_up'] = true;
            }
        }else{
            $result['quizzes'] = QuizCollection::make(Quiz::whereIn('id',collect($quiz)->pluck('id')->toArray())->get(),true);
        }
        $user_info->update([
            'quiz_questions' => null
        ]);
        return response()->success($result);
    }

    // private function find_quiz_answers($quiz_id)
    // {
    //     return $quiz_option = $this->all_quiz_answers()->filter(function($item) use ($quiz_id) {
    //         return $item->quiz_id == $quiz_id && $item->is_correct;
    //     })->sortBy('id')->pluck('id')->toArray();
    // }
    //
    // private function select_submitted_answers($quiz_answer)
    // {
    //     $submitted_answers = [];
    //     foreach($quiz_answer as $key=>$answer){
    //         if($key!='quiz_id'){
    //             array_push($submitted_answers, $key);
    //         }
    //     }
    //     sort($submitted_answers);
    //     return $submitted_answers;
    // }
}
