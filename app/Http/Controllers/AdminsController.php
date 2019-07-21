<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\Post;
use App\Models\Status;
use App\Models\User;
use App\Models\PublicNotice;
use App\Models\Administration;
use DB;
use Auth;
use Carbon;
use ConstantObjects;
use StringProcess;
use CacheUser;
use App\Sosadfun\Traits\ThreadObjectTraits;
use App\Sosadfun\Traits\PostObjectTraits;
use App\Sosadfun\Traits\MessageObjectTraits;


class AdminsController extends Controller
{
    use ThreadObjectTraits;
    use PostObjectTraits;
    use MessageObjectTraits;

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
            'administratee_id' => $type==='user'? $item->id:$item->user_id,
            'record' => $record,
            'administratable_type' => $type,
            'administratable_id' => $item->id,
        ]);
        return $operation;
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
        $user = $thread->user;

        if ($var=="1"&&!$thread->is_locked){//锁帖
            $thread->update(['is_locked'=>true]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 1);
        }

        if ($var=="2"&&$thread->is_locked){//解锁
            $thread->update(['is_locked'=>false]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 2);
        }

        if ($var=="3"&&$thread->is_public){//转私密
            $thread->update(['is_public'=>false]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 3);
        }

        if ($var=="4"&&!$thread->is_public){//转公开
            $thread->update(['is_public'=>true]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 4);
        }

        if ($var=="5"){
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 5);
            $thread->delete();
        }

        if ($var=="9"){//书本/主题贴转移版块
            $old_channel = $thread->channel();
            $channel = collect(config('channel'))->keyby('id')->get($request->channel);
            if(!$channel){abort(409, '找不到这个待转频道');}
            $thread->update(['channel_id'=>$channel->id]);
            $record = $old_channel->channel_name."->".$channel->channel_name.'|'.$record;
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 9);
        }

        if ($var=="15"&&!$thread->is_bianyuan){//打边缘限制
            $thread->update(['is_bianyuan'=>true]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 15);
        }

        if ($var=="16"&&$thread->is_bianyuan){//取消边缘限制
            $thread->update(['is_bianyuan'=>false]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 16);
        }
        if($var=='21'&&!$thread->no_reply){// 阻止回复
            $thread->update(['no_reply'=>true]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 21);
        }
        if($var=='22'&&$thread->no_reply){// 允许回复
            $thread->update(['no_reply'=>false]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 22);
        }
        if ($var=="40"){// 上浮
            $thread->update(['responded_at'=>Carbon::now()]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 40);
        }

        if ($var=="41"){// 下沉
            $thread->update(['responded_at'=>Carbon::now()->subMonths(6)]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 41);
        }

        if ($var=="42"&&!$thread->recommended){// 添加推荐
            $thread->update(['responded_at'=>Carbon::now(), 'recommended'=>true]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 42);
        }

        if ($var=="43"&&$thread->recommended){// 取消推荐
            $thread->update(['recommended'=>false]);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 43);
        }

        if ($var=="44"){// 加精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->attach($tag->id);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 44);
        }

        if ($var=="45"){// 取消精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->detach($tag->id);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 45);
        }

        if($operation===0){
            return redirect()->back()->with("warning","未能处理该主题。是否未选转换板块？");
        }

        $this->clearAllThread($thread_id);
        CacheUser::clearuser($user->id);
        if($user){
            $user->remind('new_administration');
        }
        return redirect()->back()->with('success', '已经成功处理该主题');

    }

    public function postmanagement(Post $post, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'majia' => 'string|max:10'
        ]);
        $var = request('controlpost');
        $operation = 0;
        $record = StringProcess::trimtext($post->title.$post->body, 30);
        $reason = $request->reason;
        $post_id = $post->id;
        $user = $post->user;

        if ($var=="7"){//删帖
            $operation = $this->add_admin_record('post', $post, $record, $reason, 7);
            $this->delete_post($post);
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
            $operation = $this->add_admin_record('post', $post, $record, $reason, 10);
        }

        if ($var=="37"&&!$post->is_bianyuan){//转边缘
            $post->update(['is_bianyuan'=>true]);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 37);
        }
        if ($var=="38"&&$post->is_bianyuan){//转非边缘
            $post->update(['is_bianyuan'=>false]);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 38);
        }

        if ($var=="11"&&$post->fold_state===0){//折叠
            $post->update(['fold_state'=>1]);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 11);
        }
        if ($var=="12"&&$post->fold_state>0){//转不折叠
            $post->update(['fold_state'=>0]);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 12);
        }
        if($var=='32'){//32 => '回帖折+禁（回帖折叠，发帖人禁言+一天）',//?车轱辘,版务区水贴，作者楼里水贴
            $post->update(['fold_state'=>1]);
            $this->no_post_user($user,1);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 32);
        }

        if ($var=="30"){// 30 => '回帖折+禁+清（回帖折叠，发帖人禁言+1天，积分等级清零）',//一直一直车轱辘、多次在版务区不看首楼跟帖，多次在作者问题楼/他人讨论楼里问等级签到问题等情况
            $post->update(['fold_state'=>1]);
            $this->no_post_user($user,1);
            $this->clear_user_level($user);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 30);
        }
        if ($var=="34"){// 34 => '回帖折+清+封（回帖折叠，等级清零，发言人禁止登陆1天）',//？特么特别驴叫不改的违禁
            $post->update(['fold_state'=>1]);
            $this->clear_user_level($user);
            $this->no_log_user($user,1);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 34);
        }
        if ($var=="35"){// 35 => '回帖删+清+封（回帖删除，等级清零，发言人禁止登陆7天）',//？普通辱骂
            $this->clear_user_level($user);
            $this->no_log_user($user,7);
            $post->delete();
            $operation = $this->add_admin_record('post', $post, $record, $reason, 35);
        }
        if ($var=="36"){//36 => '回帖删+封（回帖删除，等级清零，发言人永久禁止登陆）',//？特别厉害的辱骂
            $this->clear_user_level($user);
            $this->no_log_user($user,500);
            $this->delete_post($post);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 36);
        }

        if($operation===0){
            return redirect()->back()->with("warning","未能处理该回帖，可能是已经处理了");
        }

        $this->clearPostProfile($post_id);
        CacheUser::clearuser($user->id);
        if($user){
            $user->remind('new_administration');
        }
        return redirect()->back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 该回帖');
    }

    private function delete_post($post)
    {
        $chapter = $post->chapter;
        if($chapter){
            $chapter->delete();
        }
        $post->delete();
    }

    private function no_post_user($user,$days=0)// 将用户禁言增加到这个天数
    {
        $user->no_posting = 1;
        $info = $user->info;
        $info->no_posting_until = $info->no_posting_until>Carbon::now() ? $info->no_posting_until->addDays($days) : Carbon::now()->addDays($days);
        $user->save();
        $info->save();
    }

    private function no_log_user($user,$days=0)// 将用户禁止登陆增加到这个天数
    {
        $user->no_logging = 1;
        $info = $user->info;
        $info->no_logging_until = $info->no_logging_until>Carbon::now() ? $info->no_logging_until->addDays($days) : Carbon::now()->addDays($days);
        $user->save();
        $info->save();
    }

    private function clear_user_level($user)// 将用户分数和盐粒清零
    {
        $user->level = 0;
        $info = $user->info;
        $info->salt = 0;
        $user->save();
        $info->save();
    }

    public function statusmanagement(Status $status, Request $request)

    {
        $this->validate($request, [
            'reason' => 'required|string',
        ]);
        $var = request('controlpost');
        $operation = 0;
        $record = StringProcess::trimtext($status->body, 30);
        $reason = $request->reason;
        $status_id = $status->id;
        $user = $status->user;

        if ($var=="61"&&$post->is_public){//转私密
            $status->update(['is_public'=>0]);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 61);
        }
        if ($var=="62"&&!$post->is_public){//转公开
            $status->update(['is_public'=>1]);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 62);
        }

        if($var=='63'){// 63 => '私+禁（动态转私密，发帖人禁言+一天）',//边限动态
            $post->update(['is_public'=>0]);
            $this->no_post_user($user,1);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 63);
        }
        if ($var=="64"){// 64 => '私+禁+清（动态转私密，发帖人禁言+1天，积分等级清零）',//多次比较严重的边限/违规动态
            $post->update(['is_public'=>0]);
            $this->no_post_user($user,1);
            $this->clear_user_level($user);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 64);
        }
        if ($var=="65"){// 65 => '删+清+封（动态删除，积分等级清零，发言人禁止登陆1天）',//违反商业性规定/恋童
            $operation = $this->add_admin_record('status', $status, $record, $reason, 65);
            $this->clear_user_level($user);
            $this->no_log_user($user,1);
            $status->delete();
        }
        if ($var=="66"){// 66 => '删+封（动态删除，发言人永久禁止登陆）',//恶意广告
            $operation = $this->add_admin_record('status', $status, $record, $reason, 66);
            $this->clear_user_level($user);
            $this->no_log_user($user,500);
            $status->delete();
        }
        if($operation===0){
            return redirect()->back()->with("warning","未能处理该动态，可能是已经处理了");
        }

        CacheUser::clearuser($user->id);
        if($user){
            $user->remind('new_administration');
        }
        return redirect()->back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 该动态');
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


    public function usermanagement(User $user, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'noposting-days' => 'required|numeric',
            'nologging-days' => 'required|numeric',
            'salt' => 'required|numeric',
            'fish' => 'required|numeric',
            'ham' => 'required|numeric',
            'level' => 'required|numeric',
        ]);
        $var = request('controluser');
        $operation = 0;
        $record = $user->name;
        $reason = $request->reason;

        if ($var=="13"){//设置禁言时间
            $operation = $this->add_admin_record('user', $user, $record.'|'.request('noposting-days').'天', $reason, 13);
            $this->no_post_user($user, request('noposting-days'));
        }
        if ($var=="14"){//解除禁言
            $operation = $this->add_admin_record('user', $user, $record, $reason, 14);
            $info =$user->info;
            $user->no_posting = 0;
            $info->no_posting_until = Carbon::now();
            $user->save();
            $info->save();
        }
        if ($var=="18"){//设置禁止登陆时间
            $operation = $this->add_admin_record('user', $user, $record.'|'.request('nologging-days').'天', $request, 18);
            $this->no_log_user($user, request('noposting-days'));
        }
        if ($var=="19"){//解除禁止登陆
            $operation = $this->add_admin_record('user', $user, $record, $reason, 19);
            $info =$user->info;
            $user->no_logging = 0;
            $info->no_logging_until = Carbon::now();
            $user->save();
            $info->save();
        }
        if ($var=="20"){//用户等级积分清零
            $operation = $this->add_admin_record('user', $user, $record, $reason, 20);
            $info = $user->info;
            $user->level = 0;
            $info->salt = 0;
            $user->save();
            $info->save();
            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="50"){// 分值管理
            if(request('salt')){
                $record .='盐粒+'.request('salt');
            }
            if(request('fish')){
                $record .='咸鱼+'.request('fish');
            }
            if(request('ham')){
                $record .='火腿+'.request('ham');
            }
            if(request('level')){
                $record .='等级+'.request('level');
            }
            $operation = $this->add_admin_record('user', $user, $record, $reason, 50);
            $info = $user->info;
            $info->salt+=(int)request('salt');
            $info->fish+=(int)request('fish');
            $info->ham+=(int)request('ham');
            $user->level+=(int)request('level');
            $user->save();
            $info->save();
        }

        if($operation===0){
            return redirect()->back()->with("warning","未能处理用户");
        }

        CacheUser::clearuser($user->id);
        if($user){
            $user->remind('new_administration');
        }
        return redirect()->back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 处理用户');
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
        $this->refreshPulicNotices();

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
