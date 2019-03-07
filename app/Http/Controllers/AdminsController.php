<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Quote;
use App\Models\Channel;
use App\Models\Label;
use App\Models\Tag;
use App\Models\Thread;
use App\Models\Chapter;
use App\Models\Post;
use App\Models\User;
use App\Models\PostComment;
use App\Models\LongComment;
use App\Models\PublicNotice;
use App\Models\Administration;
use App\Models\Book;
use App\Models\Message;
use Auth;
use Carbon\Carbon;

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
    public function quotesreview()
    {
        $quotes = Quote::orderBy('created_at', 'desc')->paginate(config('constants.index_per_page'));
        return view('admin.quotesreview', compact('quotes'));
    }

    public function longcommentsreview()
    {
        $posts = DB::table('posts')
        ->join('users','users.id','=','posts.user_id')
        ->join('threads','threads.id','=','posts.thread_id')
        ->join('channels', 'threads.channel_id','=','channels.id')
        ->join('long_comments','posts.id','=','long_comments.post_id')
        ->where([['posts.deleted_at','=',null],['channels.channel_state','<=',1],['threads.public','=',1],['posts.as_longcomment','=',1]])
        ->select('posts.*','threads.title as thread_title', 'users.name','long_comments.reviewed','long_comments.approved')
        ->orderBy('posts.created_at', 'desc')
        ->paginate(config('constants.index_per_page'));
        return view('admin.longcommentsreview', compact('posts'))->with('as_longcomments',1);
    }

    public function toggle_review_quote(Quote $quote, $quote_method)
    {
        switch ($quote_method):
            case "approve"://通过题头
            if(!$quote->approved){
                $quote->approved = 1;
                $quote->reviewed = 1;
                $quote->save();
            }
            break;
            case "disapprove"://不通过题头(已经通过了的，不允许通过；或没有评价过的，不允许通过)
            if((!$quote->reviewed)||($quote->approved)){
                $quote->approved = 0;
                $quote->reviewed = 1;
                $quote->save();
            }
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
        endswitch;
        return $quote;
    }

    public function toggle_review_longcomment(Post $post, $longcomment_method)
    {
        $longcomment = LongComment::where('post_id',$post->id)->first();
        if($longcomment){
            switch ($longcomment_method):
                case "approve"://通过长评
                if(!$longcomment->approved){
                    $longcomment->approved = 1;
                    $longcomment->reviewed = 1;
                    $longcomment->save();
                }
                break;
                case "disapprove"://不通过长评(已经通过了的，不允许通过；或没有评价过的，不允许通过)
                if((!$longcomment->reviewed)||($longcomment->approved)){
                    $longcomment->approved = 0;
                    $longcomment->reviewed = 1;
                    $longcomment->save();
                }
                break;

                default:
                echo "应该奖励什么呢？一个bug呀……";
            endswitch;
            return 'works';
        }
        return 'notwork';
    }

    public function threadmanagement(Thread $thread, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string|max:180',
        ]);
        $var = request('controlthread');
        if ($var=="1"){
            $thread->locked = !$thread->locked;
            $thread->save();
            if($thread->locked){
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '1',//1:锁帖
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
            }else{
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '2',//1:解锁
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }
        if ($var=="2"){
            $thread->public = !$thread->public;
            $thread->save();
            if(!$thread->public){
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '3',//3:转为私密
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
            }else{
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '4',//4:转为公开
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }
        if ($var=="3"){
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '5',//5:删帖
                'item_id' => $thread->id,
                'reason' => request('reason'),
                'administratee_id' => $thread->user_id,
            ]);
            $thread->delete();
            return redirect('/')->with("success","已经删帖");
        }
        if ($var=="4"){//书本/主题贴转移版块
            DB::transaction(function () use($thread){
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '9',//转移版块
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
                $label = Label::findOrFail(request('label'));
                $channel = Channel::findOrFail(request('channel'));
                if(($label)&&($label->channel_id == $channel->id)){
                    $thread->channel_id = $channel->id;
                    $thread->label_id = $label->id;
                    if($channel->channel_state!=1){
                        $thread->book->delete();
                        $thread->book_id = 0;
                    }else{
                        if($thread->book_id==0){//这篇主题本来并不算文章,新建文章
                            $book = Book::create([
                                'thread_id' => $thread->id,
                                'book_status' => 0,
                                'book_length' => 0,
                                'lastaddedchapter_at' => Carbon::now(),
                            ]);
                            $tongren = App\Models\Tongren::create(
                                ['book_id' => $book->id]
                            );
                        }else{
                            $book = Book::findOrFail($thread->book_id);
                            $book->save();
                            if($channel->id == 2){
                                $tongren = \App\Models\Tongren::firstOrCreate(['book_id' => $book->id]);
                            }
                        }
                    }
                }
            });

            $thread->save();
            return redirect()->route('thread.show', $thread)->with("success","已经转移操作");
        }
        if ($var=="5"){//打边缘限制
            $thread->bianyuan = !$thread->bianyuan;
            $thread->save();
            if($thread->bianyuan){
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '15',//15:转为边缘限制
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
            }else{
                Administration::create([
                    'user_id' => Auth::id(),
                    'operation' => '16',//16:转为非边缘限制
                    'item_id' => $thread->id,
                    'reason' => request('reason'),
                    'administratee_id' => $thread->user_id,
                ]);
            }
            return redirect()->back()->with("success","已经成功处理该主题");
        }
        return redirect()->back()->with("danger","请选择操作类型（转换板块？）");
    }
    public function postmanagement(Post $post, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'majia' => 'required|string|max:10'
        ]);
        $var = request('controlpost');//
        if ($var=="7"){//删帖
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '7',//:删回帖
                'item_id' => $post->id,
                'reason' => request('reason'),
                'administratee_id' => $post->user_id,
            ]);
            if($post->chapter_id !=0){
                Chapter::destroy($post->chapter_id);
            }
            $post->delete();
            return redirect()->back()->with("success","已经成功处理该贴");
        }
        if ($var=="10"){//修改马甲
            if (request('anonymous')=="1"){
                $post->anonymous = true;
                $post->majia = request('majia');
            }
            if (request('anonymous')=="2"){
                $post->anonymous = false;
            }
            $post->save();
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '10',//:修改马甲
                'item_id' => $post->id,
                'reason' => request('reason'),
                'administratee_id' => $post->user_id,
            ]);
            return redirect()->back()->with("success","已经成功处理该回帖");
        }
        if ($var=="11"){//折叠
            $post->fold_state = !$post->fold_state;
            $post->save();
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => ($post->fold_state? '11':'12'),//11 => '折叠帖子',12 => '解折帖子'
                'item_id' => $post->id,
                'reason' => request('reason'),
                'administratee_id' => $post->user_id,
            ]);
            return redirect()->back()->with("success","已经成功处理该回帖");
        }
        return redirect()->back()->with("warning","什么都没做");
    }
    public function postcommentmanagement(PostComment $postcomment, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
        ]);
        if(request("delete")){
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '8',//:删回帖
                'item_id' => $postcomment->id,
                'reason' => request('reason'),
                'administratee_id' => $postcomment->user_id,
            ]);
            $postcomment->delete();
            return redirect()->back()->with("success","已经成功处理该点评");
        }
        return redirect()->back()->with("warning","什么都没做");
    }
    public function advancedthreadform(Thread $thread)
    {
        $channels = Channel::all();
        $channels->load('labels');
        return view('admin.advanced_thread_form', compact('thread','channels'));
    }
    public function usermanagement(User $user, Request $request)
    {
        $this->validate($request, [
            'reason' => 'required|string',
            'days' => 'required|numeric',
            'hours' => 'required|numeric',
        ]);
        $var = request('controluser');//
        if ($var=="13"){//设置禁言时间
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '13',//:增加禁言时间
                'item_id' => $user->id,
                'reason' => request('reason'),
                'administratee_id' => $user->id,
            ]);
            $user->no_posting = Carbon::now()->addDays(request('days'))->addHours(request('hours'));
            $user->save();
            return redirect()->back()->with("success","已经成功处理该用户");
        }
        if ($var=="14"){//解除禁言
            Administration::create([
                'user_id' => Auth::id(),
                'operation' => '14',//:增加禁言时间
                'item_id' => $user->id,
                'reason' => request('reason'),
                'administratee_id' => $user->id,
            ]);
            $user->no_posting = Carbon::now();
            $user->save();
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
             'notice_body'=>$request->body,
             'user_id'=>Auth::id(),
         ]);
         DB::table('users')->increment('unread_reminders');
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
