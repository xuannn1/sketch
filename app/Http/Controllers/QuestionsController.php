<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use App\Models\Activity;
use Carbon;
use Auth;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['answer','create','store'],
        ]);
    }

    public function index(User $user)
    {
        $questions=$user->questions()->orderBy('created_at', 'desc')->paginate(config('constants.items_per_part'));
        return view('questions.index', compact('questions','user'));
    }

    public function store(Request $request, User $user)
    {
        $data = [];
        $request->validate([
            'body' => 'required|string|min:10|max:500',
        ]);
        $data['question_body'] = request('body');
        $data['user_id'] = $user->id;
        $data['questioner_ip'] = $request->getClientIp();
        if(Auth::check()){
            $data['questioner_id'] = Auth::id();
        }
        if (!$this->isDuplicateQuestion($data)){
            $question = Question::create($data);
            $question_activity = Activity::create([
               'type' => 6,
               'item_id' => $question->id,
               'user_id' => $user->id,
            ]);
            $user->increment('system_reminders');
            $user->increment('unread_reminders');
        }else{
            return back()->with('warning','一IP一天只能提一个问题，您已提交问题，请不要重复提交！');
        }
        return back()->with('success','成功提交问题！');
    }

    public function isDuplicateQuestion($data)
    {
        $last_question = Question::where('questioner_ip', $data['questioner_ip'])
        ->orderBy('id', 'desc')
        ->first();
        return (!empty($last_question) && ((strcmp($last_question->body, $data['question_body']) === 0)||($last_question->created_at>Carbon::today()->subHours(2)->toDateTimeString())));
    }

    public function answer(User $user, Question $question, Request $request)
    {
        if(Auth::check()&&Auth::id()==$question->user_id){
            $data = [];
            $this->validate($request, [
                'body' => 'required|string|max:5000',
            ]);
            $answer = Answer::updateOrCreate(
                ['question_id' => $question->id],
                ['answer_body' => request('body')]
            );
            $question->update(['answer_id'=>$answer->id]);
            return back()->with('success','成功提交回答！');
        }else{
            return back()->with('danger','请您登陆正确的账户');
        }
    }
}
