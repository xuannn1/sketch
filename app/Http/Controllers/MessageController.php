<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cache;
use Auth;
use Carbon;
use CacheUser;
use ConstantObjects;

use App\Models\Message;
use App\Sosadfun\Traits\MessageObjectTraits;

class MessageController extends Controller
{
    use MessageObjectTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        $activity_reminders =  $this->count_activity_reminders($user,$info);
        $messagebox_reminders = $this->count_messagebox_reminders($user,$info);
        $message_reminders = $info->message_reminders;

        $info->clear_column('message_reminders');

        $messages = Message::with('message_body','receiver','poster')
        ->withReceiver($user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(config('preference.messages_per_page'));

        $public_notices = $this->findPulicNotices($user->public_notice_id);

        return view('messages.message_index', compact('user','info','messages','unread_reminders','messagebox_reminders','message_reminders','public_notices'))
        ->with(['show_message_tab'=>'messages'])
        ->with(['show_dialogue_entry'=>true]);//展示“显示对话”这个标志

    }

    public function sent()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        $activity_reminders =  $this->count_activity_reminders($user,$info);
        $messagebox_reminders = $this->count_messagebox_reminders($user,$info);

        $messages = Message::with('message_body','receiver','poster')
        ->withPoster($user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(config('preference.messages_per_page'));

        return view('messages.message_sent', compact('user','info','messages','unread_reminders','messagebox_reminders'))
        ->with(['show_message_tab'=>'sent'])
        ->with(['show_dialogue_entry'=>true]);//展示“显示对话”这个标志

    }


    public function dialogue($id)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $speaker = CacheUser::user($id);
        $speaker_info = CacheUser::info($id);

        if(!$speaker||!$speaker_info){
            abort(404,'找不到对应用户');
        }

        $recent_previous_message = Message::withPoster($id)
        ->withReceiver($user->id)
        ->withInDays(2)
        ->ordered()
        ->first();

        $messages = Message::with('message_body','poster','receiver','poster')
        ->withDialogue($user->id, $speaker->id)
        ->ordered()
        ->paginate(config('preference.messages_per_page'));

        return view('messages.message_dialogue', compact('user','info','messages','speaker','speaker_info','recent_previous_message'))
        ->with(['show_dialogue_entry'=>false]);//不展示“显示对话”这个标志

    }

    public function clearupdates()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        DB::table('activities')
        ->where('user_id',$user->id)
        ->where('seen',0)
        ->update([
            'seen' =>1,
        ]);

        DB::table('messages')
        ->where('receiver_id',$user->id)
        ->where('seen',0)
        ->update([
            'seen' =>1,
        ]);

        $info->clear_column('message_reminders');
        $info->clear_column('reward_reminders');
        $info->clear_column('upvote_reminders');
        $info->clear_column('reply_reminders');
        $user->clear_column('public_notice_id');

        return redirect()->back();
    }

    public function public_notice()
    {
        if(Auth::check()){
            Auth::user()->clear_column('public_notice_id');
        }
        $public_notices = $this->findAllPulicNotices();

        return view('messages.public_notices', compact('public_notices'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'send_to' => 'required|numeric',
            'body' => 'required|string|min:10|max:2000',
        ]);
        $body = $request->body;
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        $speaker = CacheUser::user($request->send_to);
        $speaker_info = CacheUser::info($request->send_to);
        $isFollowing = $speaker->isFollowing($user->id);

        $recent_previous_message = Message::withPoster($speaker->id)
        ->withReceiver($user->id)
        ->withInDays(2)
        ->ordered()
        ->first();

        $recent_sent_message = Message::withPoster($user->id)
        ->ordered()
        ->first();

        if(!$user||!$speaker){
            return redirect()->back()->with('danger','查无此人');
        }

        if(!$user->isAdmin()&&!$user->isEditor()&&!$isFollowing&&($speaker_info->no_stranger_msg&&!$recent_previous_message)){
            return redirect()->back()->with('warning','对方未关注你，且拒收陌生人信息');
        }

        if(!$user->isAdmin()&&!$user->isEditor()&&!$isFollowing&&($info->message_limit<=0)){
            return redirect()->back()->with('warning','您的陌生人私信额度已用完');
        }

        if($recent_sent_message&&$recent_sent_message->created_at>Carbon::now()->subMinutes(15)){
            return redirect()->back()->with('warning','15分钟内只能发送一条私信');
        }

        if($recent_sent_message&&(strcmp($recent_sent_message->message_body->content, $body)=== 0)){
            return redirect()->back()->with('warning','请不要发送内容重复的私信');
        }

        DB::transaction(function()use($user, $info, $speaker, $body){
            $message_body = \App\Models\MessageBody::create([
                'content' => $body,
            ]);
            $message = Message::create([
                'poster_id' => $user->id,
                'receiver_id' => $speaker->id,
                'body_id' => $message_body->id,
            ]);
            $speaker->remind('new_message');

        });
        if(!$user->isAdmin()&&!$user->isEditor()&&!$isFollowing){
            $info->update(['message_limit'=>$info->message_limit-1]);
        }
        return redirect()->back()->with('success','已经发送私信');

    }


}
