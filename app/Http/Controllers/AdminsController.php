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

class AdminsController extends Controller
{
    //所有这些都需要用transaction，以后再说
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index()
    {
        return view('admin.index');
    }

    public function thread_admin_record($thread, $request, $operation = 0)
    {
        return Administration::create([
            'user_id' => Auth::id(),
            'operation' => $operation,
            'item_id' => $thread->id,
            'reason' => $request->reason,
            'administratee_id' => $thread->user_id,
            'record' => StringProcess::trimtext($thread->title.$thread->brief, 40),
            'administratable_type' => 'thread',
            'administratable_id' => $thread->id,
        ]);
    }
    public function threadmanagement(Thread $thread, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string|max:180',
        ]);
        $var = request('controlthread');
        if ($var=="1"){//锁帖
            if(!$thread->is_locked){
                $thread->is_locked = true;
                $thread->save();
                $this->thread_admin_record($thread, $request, 1);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }

        if ($var=="2"){//解锁
            if($thread->is_locked){
                $thread->is_locked = false;
                $thread->save();
                $this->thread_admin_record($thread, $request, 2);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }

        if ($var=="3"){//转私密
            if($thread->is_public){
                $thread->is_public = false;
                $thread->save();
                $this->thread_admin_record($thread, $request, 3);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }

        if ($var=="4"){//转公开
            if(!$thread->is_public){
                $thread->is_public = true;
                $thread->save();
                $this->thread_admin_record($thread, $request, 4);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }


        if ($var=="5"){
            $this->thread_admin_record($thread, $request, 5);
            $thread->delete();
            return redirect('/')->with("success","已经删帖");
        }
        if ($var=="9"){//书本/主题贴转移版块
            $this->thread_admin_record($thread, $request, 9);
            $channel = collect(config('channel'))->keyby('id')->get($request->channel);
            $thread->channel_id = $channel->id;
            $thread->save();
            return redirect()->route('thread.show', $thread)->with("success","已经转移操作");
        }

        if ($var=="15"){//打边缘限制
            if(!$thread->is_bianyuan){
                $thread->is_bianyuan = true;
                $thread->save();
                $this->thread_admin_record($thread, $request, 15);
            }
            return redirect()->back()->with("success","已经成功打上边缘标记");
        }

        if ($var=="16"){//取消边缘限制
            if($thread->is_bianyuan){
                $thread->is_bianyuan = false;
                $thread->save();
                $this->thread_admin_record($thread, $request, 16);
            }
            return redirect()->back()->with("success","已经成功取消边缘该主题");
        }
        if($var=='21'){// 阻止回复
            if(!$thread->no_reply){
                $thread->no_reply = 1;
                $thread->save();
                $this->thread_admin_record($thread, $request, 21);
            }
            return redirect()->back()->with("success","已经成功阻止回复主题");
        }
        if($var=='22'){// 允许回复
            if($thread->no_reply){
                $thread->no_reply = 0;
                $thread->save();
                $this->thread_admin_record($thread, $request, 22);
            }
            return redirect()->back()->with("success","已经成功允许回复主题");
        }
        if ($var=="40"){// 上浮
            $thread->responded_at = Carbon::now();
            $thread->save();
            $this->thread_admin_record($thread, $request, 40);
            return redirect()->back()->with("success","已经成功上浮该主题");
        }

        if ($var=="41"){// 下沉
            $thread->responded_at = Carbon::now()->subMonths(6);
            $thread->save();
            $this->thread_admin_record($thread, $request, 41);
            return redirect()->back()->with("success","已经下沉该主题");
        }

        if ($var=="42"){// 添加推荐
            $thread->responded_at = Carbon::now();
            $thread->recommended = true;
            $thread->save();
            $this->thread_admin_record($thread, $request, 42);
            return redirect()->back()->with("success","已经成功推荐该主题");
        }

        if ($var=="43"){// 取消推荐
            $thread->recommended = false;
            $thread->save();
            $this->thread_admin_record($thread, $request, 43);
            return redirect()->back()->with("success","已经取消推荐该主题");
        }

        if ($var=="44"){// 加精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->attach($tag->id);
            $this->thread_admin_record($thread, $request, 44);
            return redirect()->back()->with("success","已经成功对该主题加精华");
        }

        if ($var=="45"){// 取消精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $this->tags()->detach($tag->id);
            $this->thread_admin_record($thread, $request, 45);
            return redirect()->back()->with("success","已经成功对该主题取消精华");
        }

        return redirect()->back()->with("danger","请选择操作类型（转换板块？）");
    }

    public function post_admin_record($post, $request, $operation = 0)
    {
        return Administration::create([
            'user_id' => Auth::id(),
            'operation' => $operation,
            'item_id' => $post->id,
            'reason' => $request->reason,
            'administratee_id' => $post->user_id,
            'record' => StringProcess::trimtext($post->title.$post->brief, 40),
            'administratable_type' => 'post',
            'administratable_id' => $post->id,
        ]);
    }

    public function postmanagement(Post $post, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'majia' => 'required|string|max:10'
        ]);
        $var = request('controlpost');//
        if ($var=="7"){//删帖
            $this->post_admin_record($post, $request, 7);

            $chapter = $post->chapter;
            if($chapter){
                $chapter->delete();
            }
            $post->delete();
            return redirect('/')->back()->with("success","已经成功处理该贴");
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
