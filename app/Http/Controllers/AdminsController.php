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
// use App\Sosadfun\Traits\ThreadQueryTraits;

class AdminsController extends Controller
{
    use ThreadObjectTraits;
    use PostObjectTraits;
    use MessageObjectTraits;
    // use ThreadQueryTraits;

    //所有这些都需要用transaction，以后再说
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index()
    {
        return view('admin.index');
    }

    private function add_admin_record($type='', $item='', $record='', $reason='', $operation = 0, $is_public=true)
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
            'is_public' => $is_public,
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
        $record = StringProcess::trimtext('《'.$thread->title."》".$thread->brief, 40);
        $reason = $request->reason;
        $thread_id = $thread->id;
        $user = $thread->user;
        $is_public = true;

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
            $thread->keep_only_admin_tags();
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
            $tags = ConstantObjects::find_tags_by_type('编推')->pluck('id')->toArray();
            $thread->tags()->detach($tags);
            $is_public = false;
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 43, $is_public);
        }

        if ($var=="44"){// 加精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->syncWithoutDetaching($tag->id);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 44);
        }

        if ($var=="45"){// 取消精华
            $tag = ConstantObjects::find_tag_by_name('精华');
            $thread->tags()->detach($tag->id);
            $is_public = false;
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 45, $is_public);
        }

        if ($var=="46"){// 加置顶
            $tag = ConstantObjects::find_tag_by_name('置顶');
            $thread->tags()->syncWithoutDetaching($tag->id);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 46);
        }

        if ($var=="47"){// 取消置顶
            $tag = ConstantObjects::find_tag_by_name('置顶');
            $thread->tags()->detach($tag->id);
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 47);
        }

        //60 => '隐+锁+禁7+清（隐藏和锁定主题，用户禁言7天，等级清零）',//恶意发无意义书籍意图升级
        if ($var=="60"&&!$thread->is_locked){//锁帖
            $thread->update([
                'is_locked' => true,
                'is_public' => false,
            ]);
            if($user){
                $this->no_post_user($user,7);
                $this->clear_user_level($user);
            }
            $operation = $this->add_admin_record('thread',$thread, $record, $reason, 60);
        }

        if ($var=='111'){ // 111 全楼改为当前编推
            $posts = $thread->posts()->with('review.reviewee')->withType('review')->get();
            $tag = ConstantObjects::find_tag_by_name('当前编推');
            foreach($posts as $post){
                $this->editor_recommend_post($post, $tag);
            }
            $thread->update(['recommended'=>true]);
            $thread->tags()->syncWithoutDetaching($tag);
            $is_public = false;
            $operation = $this->add_admin_record('thread', $thread, $record, $reason, 111, $is_public);
        }
        if ($var=='112'){ // 112 全楼改为往期编推
            $posts = $thread->posts()->with('review.reviewee')->withType('review')->get();
            $tag = ConstantObjects::find_tag_by_name('往期编推');
            foreach($posts as $post){
                $this->editor_recommend_post($post, $tag);
            }
            $thread->update(['recommended'=>true]);
            $thread->tags()->syncWithoutDetaching($tag);
            $is_public = false;
            $operation = $this->add_admin_record('thread', $thread, $record, $reason, 112, $is_public);
        }
        if ($var=='113'){ // 113 全楼改为专题推荐
            $posts = $thread->posts()->with('review.reviewee')->withType('review')->get();
            $tag = ConstantObjects::find_tag_by_name('专题推荐');
            foreach($posts as $post){
                $this->editor_recommend_post($post, $tag);
            }
            $thread->update(['recommended'=>true]);
            $thread->tags()->syncWithoutDetaching($tag);
            $is_public = false;
            $operation = $this->add_admin_record('thread', $thread, $record, $reason, 113, $is_public);
        }
        if ($var=='114'){ // 114 全楼改为非编推
            $posts = $thread->posts()->with('review.reviewee')->withType('review')->get();
            foreach($posts as $post){
                $this->remove_editor_recommend($post);
            }
            $tags = ConstantObjects::find_tags_by_type('编推')->pluck('id')->toArray();
            $thread->update(['recommended'=>false]);
            $thread->tags()->detach($tags);
            $is_public = false;
            $operation = $this->add_admin_record('thread', $thread, $record, $reason, 114, $is_public);
        }

        if($operation===0){
            return redirect()->back()->with("warning","未能处理该主题。是否未选转换板块？");
        }

        $this->clearAllThread($thread_id);
        CacheUser::clearuser($user->id);
        if($user&&$is_public){
            $user->remind('new_administration');
        }
        if($operation===5){
            return redirect('/')->with('success', '已经成功处理该主题');
        }
        return back()->with('success', '已经成功处理该主题');

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
        $user_id = $user?$user->id:0;
        $is_public = true;

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
        if ($var=="72"){// 72 => '折+扣（回帖折叠，扣除一定虚拟物）',//管理员不愿意回复
            $record = $this->record_value_changes($record);
            $post->update(['fold_state'=>1]);
            if($this->user_value_change($post->user)){
                $operation = $this->add_admin_record('post', $post, $record, $reason, 72);
            }
        }
        if($var=='32'){//32 => '回帖折+禁（回帖折叠，发帖人禁言+一天）',//?车轱辘,版务区水贴，作者楼里水贴
            $post->update(['fold_state'=>1]);
            $this->no_post_user($user,1);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 32);
        }
        if($var=='39'){// 39 => '回帖折+禁3（回帖折叠，发帖人禁言+3天）',//?车轱辘,版务区水贴，作者楼里水贴
            $post->update(['fold_state'=>1]);
            $this->no_post_user($user,3);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 39);
        }
        if ($var=="30"){// 30 => '回帖折+禁+清（回帖折叠，发帖人禁言+1天，积分等级清零）',//在目前的等级系统里，已失效。。
            $post->update(['fold_state'=>1]);
            $this->no_post_user($user,1);
            $this->clear_user_level($user);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 30);
        }
        if ($var=="71"){// 71 => '回帖折+禁7+清（回帖折叠，发帖人禁言+7天，积分等级清零）',//攻击性不友善
            $post->update(['fold_state'=>1]);
            $this->no_post_user($user,7);
            $this->clear_user_level($user);
            $operation = $this->add_admin_record('post', $post, $record, $reason, 71);
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
        if ($var=='101'){ // 101 单项改为当前编推
            $tag = ConstantObjects::find_tag_by_name('当前编推');
            $this->editor_recommend_post($post, $tag);
            $is_public = false;
            $operation = $this->add_admin_record('post', $post, $record, $reason, 101, $is_public);
        }
        if ($var=='102'){ // 101 单项改为往期编推
            $tag = ConstantObjects::find_tag_by_name('往期编推');
            if($this->editor_recommend_post($post, $tag)){
                $is_public = false;
                $operation = $this->add_admin_record('post', $post, $record, $reason, 102, $is_public);
            }
        }
        if ($var=='103'){ // 101 单项改为专题推荐
            $tag = ConstantObjects::find_tag_by_name('专题推荐');
            if($this->editor_recommend_post($post, $tag)){
                $is_public = false;
                $operation = $this->add_admin_record('post', $post, $record, $reason, 103, $is_public);
            }
        }
        if ($var=='104'){ // 101 单项改为非编推
            if($this->remove_editor_recommend($post)){
                $is_public = false;
                $operation = $this->add_admin_record('post', $post, $record, $reason, 104, $is_public);
            }
        }

        if($operation===0){
            return redirect()->back()->with("warning","未能处理该回帖，可能是已经处理了");
        }

        $this->clearPostProfile($post_id);
        CacheUser::clearuser($user_id);
        if($user&&$is_public){
            $user->remind('new_administration');
        }
        return redirect()->back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 该回帖');
    }

    private function record_value_changes($record)
    {
        if(request('salt')){
            $record ='盐粒'.request('salt').'|'.$record;
        }
        if(request('fish')){
            $record ='咸鱼'.request('fish').'|'.$record;
        }
        if(request('ham')){
            $record ='火腿'.request('ham').'|'.$record;
        }
        if(request('level')){
            $record ='等级'.request('level').'|'.$record;
        }
        if(request('token_limit')){
            $record ='邀请码额度'.request('token_limit').'|'.$record;
        }
        return $record;
    }

    private function user_value_change($user){
        if(!$user){return false;}
        $info = $user->info;
        if(!$info){return false;}
        $info->salt+=(int)request('salt');
        $info->fish+=(int)request('fish');
        $info->ham+=(int)request('ham');
        $info->token_limit+=(int)request('token_limit');
        $user->level+=(int)request('level');
        $user->save();
        $info->save();
        return true;
    }


    private function editor_recommend_post($post, $tag)
    {
        if(!$post||!$tag||$post->type!='review'||!$post->review){
            return false;
        }
        $post->review->recommend=1;
        $post->review->editor_recommend=1;
        $post->review->rating=0;
        $post->review->save();
        if($post->review->reviewee){
            $post->review->reviewee->recommended=1;
            $post->review->reviewee->save();
            $post->review->reviewee->tags()->syncWithoutDetaching($tag->id);
            $this->clearAllThread($post->review->thread_id);
        }
        return true;
    }
    private function remove_editor_recommend($post)
    {
        if(!$post||$post->type!='review'||!$post->review){
            return false;
        }
        $post->review->editor_recommend=0;
        $post->review->save();
        if($post->review->reviewee){
            $post->review->reviewee->recommended=0;
            $post->review->reviewee->save();
            $tags = ConstantObjects::find_tags_by_type('编推')->pluck('id')->toArray();
            $post->review->reviewee->tags()->detach($tags);
            $this->clearAllThread($post->review->thread_id);
        }
        return true;
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
        if($user){
            $user->no_posting = 1;
            $info = $user->info;
            $info->no_posting_until = $info->no_posting_until>Carbon::now() ? $info->no_posting_until->addDays($days) : Carbon::now()->addDays($days);
            $user->save();
            $info->save();
        }
    }

    private function no_log_user($user,$days=0)// 将用户禁止登陆增加到这个天数
    {
        if($user){
            $user->no_logging = 1;
            $info = $user->info;
            $info->no_logging_until = $info->no_logging_until>Carbon::now() ? $info->no_logging_until->addDays($days) : Carbon::now()->addDays($days);
            $user->save();
            $info->save();
        }
    }

    private function clear_user_level($user)// 将用户分数、答题等级和盐粒清零
    {
        if($user){
            $user->level = 0;
            $user->quiz_level = 0;
            $info = $user->info;
            $info->salt = 0;
            $info->fish = 0;
            $user->save();
            $info->save();
        }
    }

    public function statusmanagement(Status $status, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
        ]);
        $var = request('controlstatus');
        $operation = 0;
        $record = StringProcess::trimtext($status->body, 40);
        $reason = $request->reason;
        $status_id = $status->id;
        $user = $status->user;

        if ($var=="61"&&$status->is_public){//转私密
            $status->update(['is_public'=>0]);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 61);
        }
        if ($var=="62"&&!$status->is_public){//转公开
            $status->update(['is_public'=>1]);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 62);
        }

        if($var=='63'){// 63 => '私+禁（动态转私密，发帖人禁言+3天）',//边限动态
            $status->update(['is_public'=>0]);
            $this->no_post_user($user,3);
            $operation = $this->add_admin_record('status', $status, $record, $reason, 63);
        }
        if ($var=="64"){// 64 => '私+禁+清（动态转私密，发帖人禁言+7天，积分等级清零）',//多次比较严重的边限/违规动态
            $status->update(['is_public'=>0]);
            $this->no_post_user($user,7);
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

        if ($var=="17"){// 17 删除动态
            $operation = $this->add_admin_record('status', $status, $record, $reason, 17);
            $status->delete();
        }
        if($operation===0){
            return back()->with("warning","未能处理该动态，可能是已经处理了");
        }

        CacheUser::clearuser($user->id);
        if($user){
            $user->remind('new_administration');
        }
        return back()->with('success', '已经成功 '.config('adminoperations')[$operation].' 该动态');
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
            $operation = $this->add_admin_record('user', $user, request('noposting-days').'天'.$record.'|', $reason, 13);
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
            $operation = $this->add_admin_record('user', $user, request('nologging-days').'天'.'|'.$record, $reason, 18);
            $this->no_log_user($user, request('nologging-days'));
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
            $record = $this->record_value_changes($record);
            if($this->user_value_change($user)){
                $operation = $this->add_admin_record('user', $user, $record, $reason, 50);
            }
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
        $name = $request->name;
        $email = $request->email;
        $users = User::with('emailmodifications')
        ->nameLike($name)
        ->emailLike($email)
        ->select('id','name','email','created_at')
        ->paginate(config('constants.items_per_page'))
        ->appends($request->only('name','email','page'));
        return view('admin.searchusers', compact('users','name','email'));
    }

    public function convert_to_old_email(User $user, $record)
    {
        $records = $user->emailmodifications;
        $this_record = $records->keyBy('id')->get($record);
        if(!$this_record){abort(403);}
        DB::transaction(function()use($user, $this_record){
            $user->forceFill([
                'password' => str_random(60),
                'remember_token' => str_random(60),
                'activated' => 0,
                'email' => $this_record->old_email,
                'no_logging' => 1,
            ])->save();
            $this_record->admin_revoked_at = Carbon::now();
            $this_record->save();
        }, 2);

        return back()->with('success','已经将邮箱复原');
    }
}
