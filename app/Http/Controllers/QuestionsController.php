<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Auth;

class QuestionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => [],
        ]);
    }

    public function create(User $user)
    {
        if ((Auth::check())&&(Auth::id()==$user->id)){
            $questions=$user->questions()->orderBy('created_at', 'desc')->paginate(config('constants.index_per_page'));
            return view('questions.index', compact('questions','user'));
        }else{
            return view('questions.create_question', compact('user'));
        }
    }

    public function store(Request $request, User $user)
    {
        $data = [];
        $this->validate($request, [
            'body' => 'required|string|max:500',
        ]);
        $data['question_body'] = request('body');
        $data['user_id'] = $user->id;
        $data['questioner_ip'] = $request->getClientIp();
        if(Auth::check()){
            $data['questioner_id'] = Auth::id();
        }
        if (!$this->isDuplicateQuestion($data)){
            $question = Question::create($data);
        }else{
            return back()->with('warning','您已提交问题，请不要重复提交！');
        }
        return back()->with('success','成功提交问题！');
    }

    public function isDuplicateQuestion($data)
    {
        $last_question = Question::where('questioner_ip', $data['questioner_ip'])
        ->orderBy('id', 'desc')
        ->first();
        return (count($last_question) && ((strcmp($last_question->body, $data['question_body']) === 0)||($last_question->created_at>Carbon::today()->subHours(2)->toDateTimeString())));
    }
}
