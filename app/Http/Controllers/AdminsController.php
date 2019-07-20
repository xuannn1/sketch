<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Status;
use App\Models\User;
use App\Models\PublicNotice;
use App\Models\Administration;
use Auth;
use Carbon;
use ConstantObject;
use StringProcess;
use App\Sosadfun\Traits\ThreadObjectTraits;
use App\Sosadfun\Traits\PostObjectTraits;

class AdminsController extends Controller
{
    use ThreadObjectTraits;
    use PostObjectTraits;

    //所有这些都需要用transaction，以后再说
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index()
    {
        return view('admin.index');
    }

    private function add_admin_record($type='', $item='', $record='', $reason='', $operation = 0)
    {
        if(!$item||$operation==0){return;}
        Administration::create([
            'user_id' => Auth::id(),
            'operation' => $operation,
            'reason' => $reason,
            'administratee_id' => $item->user_id,
            'record' => $record,
            'administratable_type' => $type,
            'administratable_id' => $item->id,
        ]);
        return (int)$operation;
    }

    public function threadmanagement(Thread $thread, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string|max:50',
        ]);
        $var = request('controlthread');
        $operation = 0;
        $record = StringProcess::trimtext($thread->title.$thread->brief, 30);
        $reason = $request->reason;
        $thread_id = $thread->id;

        if ($var=="1"&&!$thread->is_locked){//锁帖
            $thread->update(['is_locked'=>true]);
            $operation = $this->add_admin_record($thread, $record, $reason, 1);
        }

        if ($var=="2"&&$thread->is_locked){//解锁
            $thread->update(['is_locked'=>false]);
            $operation = $this->add_admin_record($thread, $record, $reason, 2);
        }

        if ($var=="3"&&$thread->is_public){//转私密
            $thread->update(['is_public'=>false]);
            $operation = $this->add_admin_record($thread, $record, $reason, 3);
        }

        if ($var=="4"&&!$thread->is_public){//转公开
            $thread->update(['is_public'=>true]);
            $operation = $this->add_admin_record($thread, $record, $reason, 4);
        }

        if ($var=="5"){
            $operation = $this->add_admin_record($thread, $record, $reason, 5);
            $thread->delete();
        }

        if ($var=="9"){//书本/主题贴转移版块
            $old_channel = $thread->channel();
            $channel = collect(config('channel'))->keyby('id')->get($request->channel);
            if(!$channel){abort(409, '找不到这个待转频道');}
            $thread->update(['channel_id'=>$channel->id]);
            $record = $old_channel->channel_name."->".$channel->channel_name.'|'.$record
            $operation = $this->add_admin_record($thread, $record, $reason, 9);
        }

        if ($var=="15"&&!$thread->is_bianyuan){//打边缘限制
            $thread->update(['is_bianyuan'=>true]);
            $operation = $this->add_admin_record($thread, $record, $reason, 15);
        }

        if ($var=="16"&&$thread->is_bianyuan){//取消边缘限制
            $thread->update(['is_bianyuan'=>false]);
            $operation = $this->add_admin_record($thread, $record, $reason, 16);
        }
        if($var=='21'&&!$thread->no_reply){// 阻止回复
            $thread->update(['no_reply'=>true]);
            $operation = $this->add_admin_record($thread, $record, $reason, 21);
        }
        if($var=='22'&&$thread->no_reply){// 允许回复
            $thread->update(['no_reply'=>false]);
            $operation = $this->add_admin_record($thread, $record, $reason, 22);
        }
        if ($var=="40"){// 上浮
            $thread->update(['responded_at'=>Carbon::now()]);
            $operation = $this->add_admin_record($thread, $record, $reason, 40);
        }

        if ($var=="41"){// 下沉
            $thread->update(['responded_at'=>Carbon::now()subMonths(6)]);
            $operation = $this->add_admin_record($thread, $record, $reason, 41);
        }

        if ($var=="42"&&!$thread->recommended){// 添加推荐
            $thread->update(['responded_at'=>Carbon::now(), 'recommended'=>true]);
            $operation = $this->add_admin_record($thread, $record, $reason, 42);
        }

        if ($var=="43"&&$thread->recommended){// 取消推荐
            $thread->update(['recommended'=>false]);
            $operation = $this->add_admin_record($thread, $record, $reason, 43);
        }

        if ($var=="44"){// 加精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->attach($tag->id);
            $operation = $this->add_admin_record($thread, $record, $reason, 44);
        }

        if ($var=="45"){// 取消精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->detach($tag->id);
            $operation = $this->add_admin_record($thread, $record, $reason, 45);
        }

        if($operation===0){
            return redirect()->back()->with("warning","未能处理该主题。是否未选转换板块？");
        }

        $this->clearAllThread($thread_id);
        return redirect()->back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 该主题');

    }

    public function postmanagement(Post $post, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'majia' => 'required|string|max:10'
        ]);
        $var = request('controlpost');
        $operation = 0;
        $record = StringProcess::trimtext($post->title.$post->body, 30);
        $reason = $request->reason;
        $post_id = $post->id;

        $var = request('controlpost');//
        if ($var=="7"){//删帖
            $operation = $this->add_admin_record($post, $record, $reason, 7);

            $chapter = $post->chapter;
            if($chapter){
                $chapter->delete();
            }
            $post->delete();
        }
        if ($var=="10"){//修改马甲
            if (request('is_anonymous')=="1"){
                $post->is_anonymous = true;
                $post->majia = request('majia');
            }
            if (request('is_anonymous')=="2"){
                $post->is_anonymous = false;
            }
            $post->save();
            $this->post_admin_record($post, $request, 10);
            return redirect()->back()->with("success","已经成功处理该回帖");
        }
        if ($var=="11"){//折叠
            if(!$post->fold_state){
                $this->post_admin_record($post, $request, 11);
                $post->fold_state = 1;
                $post->save();
            }
            return redirect()->back()->with("success","已经成功处理该回帖");
        }
        if ($var=="12"){//解折叠
            if($post->fold_state>1){
                $this->post_admin_record($post, $request, 12);
                $post->fold_state = 0;
                $post->save();
            }
            return redirect()->back()->with("success","已经成功处理该回帖");
        }

        if ($var=="30"){//无意义水贴套餐：禁言、折叠、积分清零
            $this->post_admin_record($post, $request, 30);
            $post->fold_state = 1;
            $user=$post->user;
            $info =$user->info;
            $user->no_posting = 1;
            $info->no_posting_until = Carbon::parse($info->no_posting_until)->addDays(1);
            $user->level = 0;
            $user->salt = 0;
            $user->save();
            $info->save();
            $post->save();
            return redirect()->back()->with("success","已经成功处理该回帖");
        }

        $this->clearPostProfile($post_id);
        return redirect()->back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 该回帖');
    }

    public function statusmanagement(Status $status, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
        ]);
        if(request("delete")){
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '17',//:删动态
                'item_id' => $status->id,
                'reason' => request('reason'),
                'administratee_id' => $status->user_id,
                'record' => StringProcess::trimtext($status->body, 40),
                'administratable_type' => 'status',
                'administratable_id' => $status->id,
            ]);
            $status->delete();

            return redirect('/')->with("success","已经成功处理该动态");
        }
        return redirect()->back()->with("warning","什么都没做");
    }

    public function threadform(Thread $thread)
    {
        return view('admin.thread_form', compact('thread'));
    }

    public function userform(User $user)
    {
        return view('admin.user_form', compact('user'));
    }

    public function postform(Post $post)
    {
        return view('admin.post_form', compact('post'));
    }

    public function statusform(Status $status)
    {
        return view('admin.status_form', compact('status'));
    }

    public function user_admin_record($user, $request, $operation = 0)
    {
        return Administration::create([
            'user_id' => Auth::id(),
            'operation' => $operation,
            'item_id' => $user->id,
            'reason' => $request->reason,
            'administratee_id' => $user->id,
            'record' => $user->name,
            'administratable_type' => 'user',
            'administratable_id' => $user->id,
        ]);
    }
    public function usermanagement(User $user, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'noposting-days' => 'required|numeric',
            'noposting-hours' => 'required|numeric',
            'nologging-days' => 'required|numeric',
            'nologging-hours' => 'required|numeric',
            'salt' => 'required|numeric',
            'fish' => 'required|numeric',
            'ham' => 'required|numeric',
            'level' => 'required|numeric',
        ]);
        $var = request('controluser');//
        if ($var=="13"){//设置禁言时间
            $this->user_admin_record($user, $request, 13);

            $info =$user->info;
            $user->no_posting = 1;
            $info->no_posting_until = Carbon::parse($info->no_posting_until)->addDays(1);
            $user->save();
            $info->save();

            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="14"){//解除禁言
            $this->user_admin_record($user, $request, 14);
            $info =$user->info;
            $user->no_posting = 0;
            $info->no_posting_until = Carbon::now();
            $user->save();
            $info->save();
            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="18"){//设置禁止登陆时间
            $this->user_admin_record($user, $request, 18);

            $info =$user->info;
            $user->no_logging = 1;
            $info->no_logging_until = Carbon::parse($info->no_logging_until)->addDays(1);
            $user->remember_token = null;
            $user->save();
            $info->save();

            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="19"){//解除禁止登陆
            $this->user_admin_record($user, $request, 19);
            $info =$user->info;
            $user->no_logging = 0;
            $info->no_logging_until = Carbon::now();
            $user->save();
            $info->save();
            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="20"){//用户等级积分清零
            $this->user_admin_record($user, $request, 20);
            $info = $user->info;
            $user->level = 0;
            $user->salt = 0;
            $user->save();
            $info->save();
            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="50"){// 分值管理
            $this->user_admin_record($user, $request, 50);
            $info = $user->info;
            $info->salt+=(int)request('salt');
            $info->fish+=(int)request('fish');
            $info->ham+=(int)request('ham');
            $user->level+=(int)request('level');
            $user->save();
            $info->save();
            return redirect()->back()->with("success","已经成功处理该用户");
        }

        return redirect()->back()->with("warning","什么都没做");
    }

    public function sendpublicnoticeform()
    {
        return view('admin.send_publicnotice');
    }

    public function sendpublicnotice(Request $request)
    {
        $this->validate($request, [
            'body' => 'required|string|max:20000|min:10',
         ]);
         $public_notice = PublicNotice::create([
             'body'=>$request->body,
             'user_id'=>Auth::id(),
         ]);
         DB::table('system_variables')->update(['latest_public_notice_id' => $public_notice->id]);
         return redirect()->back()->with('success','您已成功发布公共通知');
    }

    public function create_tag_form(){
        $labels_tongren = Label::where('channel_id',2)->get();
        $tags_tongren_yuanzhu = Tag::where('tag_group',10)->get();
        $tags_tongren_cp = Tag::where('tag_group',20)->get();
        return view('admin.create_tag',compact('labels_tongren','tags_tongren_yuanzhu','tags_tongren_cp'));
    }

    public function store_tag(Request $request){
        if($request->tongren_tag_group==='1'){//同人原著tag
            Tag::create([
                'tag_group' => 10,
                'tagname'=>$request->tongren_yuanzhu,
                'tag_explanation'=>$request->tongren_yuanzhu_full,
                'label_id' =>$request->label_id,
            ]);
            return redirect()->back()->with("success","成功创立同人原著tag");
        }
        if($request->tongren_tag_group==='2'){//同人CPtag
            Tag::create([
                'tag_group' => 20,
                'tagname'=>$request->tongren_cp,
                'tag_explanation'=>$request->tongren_cp_full,
                'tag_belongs_to' =>$request->tongren_yuanzhu_tag_id,
            ]);
            return redirect()->back()->with("success","成功创立同人CPtag");
        }
        return redirect()->back()->with("warning","什么都没做");
    }

    public function searchusersform(){
        return view('admin.searchusersform');
    }
    public function searchusers(Request $request){
        $users = User::nameLike($request->name)
        ->emailLike($request->email)
        ->select('id','name','email','created_at','last_login')
        ->paginate(config('constants.items_per_page'))
        ->appends($request->only('name','email','page'));
        return view('admin.searchusers', compact('users'));
    }
}
