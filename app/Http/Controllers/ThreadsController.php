<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Http\Requests\StoreThread;
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
      ->with('creator','label','channel','lastpost')->simplePaginate(config('constants.index_per_page'));
      return view('threads.index', compact('threads'))->with('show_as_collections', false);
   }

   public function show(Thread $thread, Request $request)
   {
       $posts = Post::allPosts($thread->id,$thread->post_id)->userOnly(request('useronly'))->withOrder('oldest')
       ->with('owner','reply_to_post.owner','comments.owner')->paginate(config('constants.items_per_page'));
       $thread->increment('viewed');
       $thread->load('label','channel');
       return view('threads.show', compact('thread', 'posts'))->with('defaultchapter',0);
   }

   public function createThreadForm(Channel $channel)
   {
      $labels = $channel->labels();
      if ($channel->id<=2){
         return view('books.create');
      }
      return view('threads.create_thread', compact('labels', 'channel'));
   }

   public function store(StoreThread $form, Channel $channel)
   {
      $thread = $form->generateThread($channel->id);
      if($thread->label_id == 50){
          $thread->registerhomework();
      }
      return redirect()->route('thread.show', $thread->id)->with("success", "您已成功发布主题");
   }

   public function edit(Thread $thread)
   {
     return view('threads.thread_edit', compact('thread'));
   }

   public function update(StoreThread $form, Thread $thread)
   {
      if (Auth::id() == $thread->user_id){
         $form->updateThread($thread);
         return redirect()->route('thread.show', $thread->id)->with("success", "您已成功修改主题");
      }else{
         return redirect()->route('error', ['error_code' => '403']);
      }
   }
   public function showpost(Post $post)
   {
      $thread = $post->thread;
      $totalposts = Post::allPosts($thread->id,$thread->post_id)
          ->where('created_at', '<', $post->created_at)
          ->count();
      $page = intdiv($totalposts, Config::get('constants.items_per_page'))+1;
      $url = 'threads/'.$thread->id.'?page='.$page.'#post'.$post->id;
      return redirect($url);
   }
}
