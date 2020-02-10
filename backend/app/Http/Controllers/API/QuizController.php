<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\PaginateResource;
use App\Http\Resources\QuizCollection;
use App\Http\Resources\QuizResource;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizOption;
use DB;
use Auth;
use Validator;
use App\Sosadfun\Traits\QuizObjectTraits;
use Carbon;
use Illuminate\Validation\Rule;

class QuizController extends Controller
{
    use QuizObjectTraits;

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin')->only('index','store','update','destroy','show');

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'quizzes'=> 'required|array',
            'quizzes.*.body' => 'required|string|max:6000',
            'quizzes.*.hint' => 'nullable|string|max:6000',
            'quizzes.*.is_online'=> 'nullable|boolean',
            'quizzes.*.type' => [
                'required',
                'string',
                Rule::in(array_keys(config('constants.quiz_types')))
            ],
            'quizzes.*.level'=> 'integer|required_if:quizzes.*.type,'.implode(',',config('constants.quiz_has_level')),
            'quizzes.*.option'=> 'array|required_if:quizzes.*.type,'.implode(',',config('constants.quiz_has_option')),
            'quizzes.*.option.*.body' => 'required|string|max:190',
            'quizzes.*.option.*.explanation' => 'required|string|max:190',
            'quizzes.*.option.*.is_correct' => 'nullable|boolean',
        ]);
    }

    public function index(Request $request)
    {
        $quizzes = Quiz::with('quiz_options')->withQuizOnlineStatus($request->quiz_status);;
        if (isset($request->quiz_level) && ($request->quiz_level || $request->quiz_level == '0')&& $request->quiz_level != '') {
            $quizzes = $quizzes->withQuizLevelRange($request->quiz_level);
        }
        if (isset($request->quiz_type) && $request->quiz_type && $request->quiz_type != '') {
            $quizzes = $quizzes->withQuizTypeRange($request->quiz_type);
        }

        $quizzes = $quizzes->orderBy('id','desc')->paginate(config('preference.quiz_per_page'));
        return response()->success([
            'quizzes' => QuizCollection::make($quizzes),
            'paginate' => new PaginateResource($quizzes),
        ]);
    }

    public function store(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->error($validator->errors(), 422);
        }

        $succeeded_quiz = [];
        $failed_quiz = [];
        DB::transaction(function () use ($request, &$succeeded_quiz, &$failed_quiz){
            foreach ($request->quizzes as $index => $quiz) {
                $result = $this->save_quiz($quiz);
                if ($result) {
                    $succeeded_quiz[] = $result;
                } else {
                    $failed_quiz[] = $index;
                }
            }
        });
        return response()->success([
            'quizzes' => QuizCollection::make(collect($succeeded_quiz)),
            'failed' => $failed_quiz
        ]);
    }

    public function show($id)
    {
        // $quiz = Quiz::on('mysql::write')->find($id);
        // $quiz->load('quiz_options');
        // return view('quiz.show', compact('quiz'));
    }

    public function update($id, Request $request)
    {
        $orig_quiz = Quiz::on('mysql::write')->find($id);
        if (!$orig_quiz) {
            abort(404,'没有找到对应的quiz_id。');
        }
        $validator = $this->validator(['quizzes'=>[$request->all()]]);
        if ($validator->fails()) {
            return response()->error($validator->errors(), 422);
        }

        $quiz = $request->all();
        if (in_array($quiz['type'],config('constants.quiz_has_option')) xor in_array($orig_quiz['type'],config('constants.quiz_has_option'))) {
            abort(422, '不可以将'.$orig_quiz['type'].'类型更改为'.$quiz['type'].'类型。');
        }
        $result = null;
        DB::transaction(function () use ($quiz, $orig_quiz, &$result) {
            $result = $this->save_quiz($quiz, $orig_quiz);
        });
        if (!$result) {
            abort(422, '没有选择至少一个正确选项。');
        }
        return response()->success(new QuizResource($result));
    }

    public function getQuiz(Request $request)
    {
        $user = auth('api')->user();
        if (!$user) {
            abort(401,'用户未登录。');
        }
        $level = (int)$request->level ?? 0;
        $quizzes = $this->random_quizzes($level, 'level_up', config('constants.quiz_test_number',5));
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
        $is_passed = $this->check_quiz_passed_or_not($quiz, $user_info->quiz_questions, config('constants.quiz_test_number',5));
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

    private function save_quiz($quiz, $orig_quiz = null) {
        // 先检查有没有至少一个正确选项
        if (!array_key_exists('option', $quiz)) {
            $quiz['option'] = [];
        }
        $has_correct_answer = false;
        foreach ($quiz['option'] as $option) {
            if (array_key_exists('is_correct', $option) && $option['is_correct']) {
                $has_correct_answer = true;
            }
        }
        if (!$has_correct_answer && in_array($quiz['type'], config('constants.quiz_has_option'))) {
            return null;
        }
        $quiz_data['body'] = $quiz['body'];
        $quiz_data['quiz_level'] = $quiz['level'] ?? -1;
        $quiz_data['hint'] = $quiz['hint'] ?? null;
        $quiz_data['type'] = $quiz['type'];
        $quiz_data['is_online'] = $quiz['is_online'] ?? true;
        $new_quiz = null;
        if (!$orig_quiz) {
            $new_quiz = Quiz::create($quiz_data);
        } else {
            $orig_quiz->update($quiz_data);
            $new_quiz = $orig_quiz->refresh();
            $orig_quiz->quiz_options()->delete();
        }
        foreach ($quiz['option'] as $index => $option) {
            $option_data = [
                'quiz_id' => $new_quiz->id,
                'body' => $option['body'],
                'explanation' => $option['explanation'],
                'is_correct' => $option['is_correct'] ?? false
            ];
            QuizOption::create($option_data);
        }
        return $new_quiz;
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
