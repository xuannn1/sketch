<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Models\Thread;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Channel;
use Carbon\Carbon;
use Auth;
use App\Models\User;
// use App\RegisterHomework;

class threadsController extends Controller
{
   public function __construct()
   {
      $this->middleware('auth')->except(['index', 'show', 'showpost']);
   }
   public function index(Request $request)
   {
      $threads = Thread::canSee()->inChannel(request('channel'))->inLabel(request('label'))->withOrder('recentresponded')
      ->with('creator','label','channel','lastpost')->simplePaginate(config('index_per_page'));
      return view('threads.index', compact('threads'))->with('show_as_collections', false);
   }

   public function show(Thread $thread, Request $request)
   {
      $thread->increment('viewed');
      $posts = Post::where([
         ['thread_id', '=', $thread->id],
         ['id', '<>', $thread->post_id]
         ])
         ->with(['owner','reply_to_post.owner','chapter','comments.owner'])
         ->oldest()
         ->paginate(Config::get('constants.items_per_page'));
      $ip = $request->getClientIp();
      $only = false;
      $chapter_replied = true;
      //dd($posts);
      $book_info = Config::get('constants.book_info');
      $book=$thread->book;
      $thread->load(['channel','creator', 'tags', 'label', 'mainpost.comments.owner']);
      if ($thread->homework_id){
        $homework = $thread->homework;
        $registered = $homework->registered_students();
        $register_homeworks = $homework->registerhomeworks->load(['thread.posts.owner','student']);
        return view('threads.show', compact('book', 'thread', 'posts', 'ip', 'only', 'book_info','chapter_replied','homework','registered','register_homeworks'));
      }else{
        return view('threads.show', compact('book', 'thread', 'posts', 'ip', 'only', 'book_info','chapter_replied'));
      }
   }

   public function useronly(Thread $thread, User $user, Request $request)
   {
      $thread->load(['channel', 'creator', 'label']);
      $thread->increment('viewed');
      $posts = Post::where([
         ['thread_id', '=', $thread->id],
         ['id', '<>', $thread->post_id],
         ['user_id', '=', $user->id]
         ])->with(['owner', 'comments.owner', 'reply_to_post.owner'])
         ->oldest()
         ->paginate(Config::get('constants.items_per_page'));
      $ip = $request->getClientIp();
      $only = true;
      return view('threads.show', compact('thread', 'posts', 'ip','only'));
   }

   public function createThreadForm(Channel $channel)
   {
      $labels = $channel->labels();
      if ($channel->id<=2){
         return view('books.create');
      }
      return view('threads.create_thread', compact('labels', 'channel'));
   }

   public function store(Request $request, Channel $channel)
   {
      $this->validate($request, [
          'title' => 'required|string|max:30',
          'brief' => 'required|string|max:50',
          'body' => 'required|string|min:10',
          'label' => 'required',
          'majia' => 'string|max:10',
        ]);
      if(request('anonymous')){
         $anonymous = true;
         $majia = request('majia');
         Auth::user()->update(['majia'=>$majia]);
      }else{
         $anonymous = false;
         $majia = null;
      }
      $public = request('public')? true: false;
      $noreply = request('noreply')? true:false;
      $markdown = request('markdown')? true: false;
      $indentation = request('indentation')? true: false;
      $thread = Thread::create([
         'title' => request('title'),
         'brief' => request('brief'),
         'body' => request('body'),
         'channel_id' => $channel->id,
         'user_id' => auth()->id(),
         'anonymous' => $anonymous,
         'majia' => $majia,
         'lastresponded_at' => Carbon::now(),
         'label_id' => request('label'),
         'public' => $public,
         'noreply' => $noreply,
      ]);

      $post = Post::create([
         'user_id' => auth()->id(),
         'body' => null,
         'thread_id' => $thread->id,
         'markdown' => $markdown,
         'indentation' => $indentation,
      ]);
      $thread->update(['post_id'=>$post->id]);
      if($thread->label_id == 50){
         DB::table('register_homeworks')
         ->join('homeworks','homeworks.id','=','register_homeworks.homework_id')
         ->where('register_homeworks.user_id','=',Auth::id())
         ->where('homeworks.active','=',true)
         ->update(['register_homeworks.thread_id' => $thread->id]);
      }
      return redirect()->route('thread.show', $thread->id)->with("success", "您已成功发布主题");
   }
   public function edit(Thread $thread)
   {
     return view('threads.thread_edit', compact('thread'));
   }
   public function update(Request $request, Thread $thread)
   {
      if (Auth::id() == $thread->user_id){
         $this->validate($request, [
             'title' => 'required|string|max:20',
             'brief' => 'required|string|max:40',
             'body' => 'required|string|min:20',
             'label' => 'required',
             'majia' => 'string|max:10',
           ]);
         $anonymous = request('anonymous')? true: false;
         $public = request('public')? true: false;
         $noreply = request('noreply')? true:false;
         $markdown = request('markdown')? true: false;
         $indentation = request('indentation')? true: false;
         $thread->update([
            'title' => request('title'),
            'brief' => request('brief'),
            'body' => request('body'),
            'label_id' => request('label'),
            'anonymous' => $anonymous,
            'public' => $public,
            'noreply' => $noreply,
            'edited_at' => Carbon::now(),
           ]);
           $post = $thread->mainpost;
           $post->update([
             'markdown'=>$markdown,
             'indentation'=>$indentation,
           ]);
         return redirect()->route('thread.show', $thread->id)->with("success", "您已成功修改主题");
      }else{
         return redirect()->route('error', ['error_code' => '403']);
      }
   }
   public function showpost(Post $post)
   {
      $thread = $post->thread;
      $totalposts = Post::where([
         ['thread_id', '=', $post->thread_id],
         ['id', '<>', $thread->post_id],
         ['created_at', '<', $post->created_at]
         ])->count();
      $page = intdiv($totalposts, Config::get('constants.items_per_page'))+1;
      $url = 'threads/'.$thread->id.'?page='.$page.'#post'.$post->id;
      return redirect($url);
   }
}
