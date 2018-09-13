<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Thread;
use App\Models\Channel;
use Auth;
use App\Models\RegisterHomework;
use App\Models\Message;
use App\Models\User;
use App\Models\Post;
use App\Models\Homework;

use Illuminate\Support\Facades\DB;

class HomeworksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function create()
    {
        return view('homeworks/create');
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'requirement' => 'required|string|min:10',
            'hold_sangdian' => 'required|numeric|min:0|max:500',
            'register_number' => 'required|numeric|min:5|max:50',
            'start_time' => 'required|date',
        ]);
        $starttime = Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time, 'Asia/Shanghai')->setTimezone('UTC');
        $homework = Homework::create([
            'hold_sangdian' => $request->hold_sangdian,
            'register_number' => 5,
            'register_at' => $starttime->toDateTimeString(),
            'register_number_b' => $request->register_number-5,
            'register_at_b' => $starttime->addHours(12)->toDateTimeString(),
        ]);
        $channel = Channel::find(4);
        $thread = Thread::create([
            'title' => "第{$homework->id}次作业报名入口",
            'channel_id' => 4,
            'user_id' => $user->id,
            'anonymous' => false,
            'lastresponded_at' => Carbon::now(),
            'label_id' => 12,//needs adjust for now's datafile
            'brief' => ' ',
            'homework_id' => $homework->id,
        ]);
        $thread->update_channel();
        $markdown = request('markdown')? true: false;
        $post = Post::create([
            'user_id' => auth()->id(),
            'thread_id' => $thread->id,
            'markdown' => $markdown,
            'body'=>request('requirement'),
        ]);
        $thread->update(['post_id'=>$post->id]);
        return redirect()->route('thread.show', $thread->id)->with("success", "您已成功发布主题");
    }
    public function register(Homework $homework, Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'majia' => 'string|max:10',
        ]);
        if($homework->registered->find($user->id)){
            return back()->with("info", "您已报名，请勿重复报名");
        }elseif($user->no_registration>Carbon::now()){
            return back()->with("danger", "抱歉，您被暂时禁止报名");
        }else{
            if($homework->register_at>Carbon::now()){//两波报名都未开始
                return back()->with("info", "抱歉，报名还未开始");
            }elseif($homework->register_at_b>Carbon::now()){//第一波报名开始了，第二波没有，只看按照第一波的要求能不能报名
                if($homework->register_number<=0){
                    return back()->with("info", "抱歉，本波报名人数已满，无法报名。");
                }elseif($user->sangdian<$homework->hold_sangdian){
                    return back()->with("info", "抱歉，您的丧点不足，无法报名。");
                }else{
                    $homework->decrement('register_number');//第一波，报名了，去除第一波名额
                }
            }else{//第二波已经开始
                if(($homework->register_number+$homework->register_number_b)<=0){
                    return back()->with("info", "抱歉，报名人数已满，无法报名。");
                }elseif($user->sangdian<$homework->hold_sangdian){
                    return back()->with("info", "抱歉，您的丧点不足，无法报名。");
                }else{//去除无论哪一波报名名额的计算
                    if ($homework->register_number>0){
                        $homework->decrement('register_number');
                    }else{
                        $homework->decrement('register_number_b');
                    }
                }
            }
            //在之前的条件中，都没有离开的，应该是已经报上名了，那么下面给予权限
            if ($user->group<15){//本来权限不足的，开通作业期间临时权限，但是扣除丧点
                $user->group=15;
                $user->sangdian -= $homework->hold_sangdian;
                $user->save();
            }
            $registration = RegisterHomework::create([
                'homework_id' => $homework->id,
                'user_id' => $user->id,
                'majia' => request('majia'),
            ]);//报名参加本次作业
            return redirect()->back()->with("success", "您已成功加入作业小组");
        }
        return redirect()->back()->with("danger", "出现了问题");
    }
    public function sendreminderform(Homework $homework)
    {
        if($homework->active){
            $homework->load('registered');
            return view('homeworks.send_reminder', compact('homework'));
        }else{
            return redirect()->back()->with("danger", "这次作业已失效");
        }
    }
    public function sendreminder(Homework $homework, Request $request)
    {
        $this->validate($request, [
            'body' => 'required|string|max:20000|min:10',
        ]);

        if($homework->active){
            $students = request('students');
            $message_body = DB::table('message_bodies')->insertGetId([
                'content' => request('body'),
                'group_messaging' => 1,
            ]);
            foreach($students as $student){
                $receiver = User::find($student);
                if(($receiver)&&($homework->registered->find($student))){//validate that this student 1 exist 2 registered for homework
                    Message::create([
                        'message_body' => $message_body,
                        'poster_id' => Auth::id(),
                        'receiver_id' => $receiver->id,
                        'private' => false,
                    ]);
                    $receiver->increment('message_reminders');
                    $receiver->increment('unread_reminders');
                }
            }
            return redirect()->route('homework.show', $homework->id)->with('success','您已成功发布作业通知');
        }else{
            return redirect()->back()->with("danger", "这次作业已失效");
        }
    }
    public function index()
    {
        $homeworks = Homework::with(['registerhomeworks.student'])->orderBy('id','desc')->paginate(config('constants.items_per_page'));
        return view('homeworks.index', compact('homeworks'));
    }
    public function show(Homework $homework)
    {
        $homework->load(['thread.creator','registerhomeworks.student','registerhomeworks.thread.posts.owner']);
        return view('homeworks.show', compact('homework'));
    }
    public function deactivate(Homework $homework)
    {
        if ($homework->active){
            $threads = DB::table('register_homeworks')
            ->join('threads','threads.id','=','register_homeworks.thread_id')
            ->where('register_homeworks.homework_id','=',$homework->id)
            ->where('threads.label_id','=',50)
            ->update(['threads.label_id' => 51, 'threads.locked'=>true]);//所有“本次”作业的帖子

            $registered = DB::table('register_homeworks')
            ->join('users','users.id','=','register_homeworks.user_id')
            ->where([['users.group','<=',15],['register_homeworks.homework_id','=',$homework->id]])
            ->update(['users.group' => 10]);

            $homework->active =false;
            $homework->registration_on = false;
            $homework->save();
            return redirect()->back()->with("success", "您已成功结束这次作业活动");
        }else{
            return redirect()->back()->with("danger", "这次作业已失效");
        }
    }
    public function rewardsform(Homework $homework)
    {
        if($homework->active){
            $registered = $homework->registered_students();
            return view('homeworks.send_rewards', compact('homework','registered'));
        }else{
            return redirect()->back()->with("danger", "这次作业已失效");
        }
    }
    public function rewards(Homework $homework, Request $request)
    {
        if($homework->active){
            $registered = $homework->registered_students();
            $base = $homework->hold_sangdian;
            foreach($registered as $student){
                $rewards=request($student->id);
                $user = User::find($student->id);
                if($rewards=='1'){//super-reward
                    DB::transaction(function()use($user, $base){
                        $user->reward('homework_excellent', $base);
                    });
                }elseif($rewards=='2'){//regular-reward
                    DB::transaction(function()use($user, $base){
                        $user->reward('homework_regular', $base);
                    });
                }elseif($rewards=='4'){//punishmeng-1months
                    $user->no_registration = Carbon::now()->addMonth(2);
                }elseif($rewards=='5'){//punishmeng-3months
                    $user->no_registration = Carbon::now()->addMonths(6);
                }
                $user->save();
            }
            return redirect()->route('homework.show', $homework->id)->with('success','您已成功发放奖励');
        }else{
            return redirect()->back()->with("danger", "这次作业已失效");
        }
    }
}
