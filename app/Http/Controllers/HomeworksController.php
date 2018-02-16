<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Thread;
use Auth;
use App\RegisterHomework;
use App\Message;
use App\User;
use App\Post;
use App\Homework;
use Illuminate\Support\Facades\Config;
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
      //dd($request);
      $user = Auth::user();
      $this->validate($request, [
          'requirement' => 'required|string|min:10',
       ]);
      $homework = Homework::create();
      $thread = Thread::create([
         'title' => "第{$homework->id}次作业报名入口",
         'channel_id' => 4,
         'user_id' => $user->id,
         'anonymous' => false,
         'lastresponded_at' => Carbon::now(),
         'label_id' => 11,//needs adjust for now's datafile
         'brief' => ' ',
         'homework_id' => $homework->id,
         'body'=>request('requirement'),
      ]);
      $markdown = request('markdown')? true: false;
      $post = Post::create([
         'user_id' => auth()->id(),
         'body' => null,
         'thread_id' => $thread->id,
         'markdown' => $markdown,
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
       if($user->no_registration>Carbon::now()){
          return back()->with("danger", "抱歉，您被暂时禁止报名");
       }else{
          if($homework->registered->count() >= $homework->participants){
             return back()->with("info", "抱歉，报名已满");
          }elseif($homework->registered->find($user->id)){
             return back()->with("info", "您已报名，请勿重复报名");
          }else{
             if ($user->group<15){//开通作业期间临时权限
                $user->group=15;
                $user->save();
             }
             $registration = RegisterHomework::create([
                'homework_id' => $homework->id,
                'user_id' => $user->id,
                'majia' => request('majia'),
             ]);//报名参加本次作业
             return redirect()->back()->with("success", "您已成功加入作业小组");
          }
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
              'content' => request('body',
              'group_messaging' => 1,
           )]);
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
            }
         }
         return redirect()->route('homework.show', $homework->id)->with('success','您已成功发布作业通知');
      }else{
         return redirect()->back()->with("danger", "这次作业已失效");
      }
   }
   public function index()
   {
      $homeworks = Homework::with(['registerhomeworks.student'])->orderBy('id','desc')->paginate(Config::get('constants.items_per_page'));
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
      $reward_base = 1;
      if($homework->active){
         $registered = $homework->registered_students();
         foreach($registered as $student){
            $rewards=request($student->id);
            $user = User::find($student->id);
            if($rewards=='1'){//super-reward
               $user->increment('jifen', 50*$reward_base);
               $user->increment('shengfan', 50*$reward_base);
               $user->increment('xianyu', 25*$reward_base);
               $user->increment('sangdian', 10*$reward_base);
            }elseif($rewards=='2'){//regular-reward
               $user->increment('jifen', 20*$reward_base);
               $user->increment('shengfan', 20*$reward_base);
               $user->increment('xianyu', 10*$reward_base);
               $user->increment('sangdian', 5*$reward_base);
            }elseif($rewards=='4'){//punishmeng-1months
               $user->no_registration = Carbon::now()->addMonth(1);
            }elseif($rewards=='5'){//punishmeng-3months
               $user->no_registration = Carbon::now()->addMonths(3);
            }
            $user->save();
         }
         return redirect()->route('homework.show', $homework->id)->with('success','您已成功发放奖励');
      }else{
         return redirect()->back()->with("danger", "这次作业已失效");
      }
   }
}
