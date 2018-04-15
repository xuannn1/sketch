<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Auth;
use App\Models\Collection;
use App\Models\Thread;
use Carbon\Carbon;
use App\Models\Follower;

class CollectionsController extends Controller
{
   public function __construct()
   {
     $this->middleware('auth');
   }
   public function store(Thread $thread)
   {
      $user = Auth::user();
      $collection = $thread->collection($user);
      if($collection){
         return back()->with("info", "您已收藏本文，无需重复收藏~");
      }
      $collecttion = Collection::create([
        'user_id' => $user->id,
        'thread_id' => $thread->id,
     ]);
     $thread->increment('collection');
     $user->update(['lastresponded_at' => Carbon::now()]);
     return back()->with("success", "您已成功收藏本文");
   }

   public function cancel(Request $request)
   {
      $user = Auth::user();
      $thread = Thread::find(request('thread_id'));
      $collection = $thread->collection($user);
      if($collection){
         $collection->delete();
         $thread->decrement('collection');
      }else{
         return redirect()->route('error', ['error_code' => '409']);
      }
   }
   public function togglekeepupdate(Request $request)
   {
      $user = Auth::user();
      $thread = Thread::find(request('thread_id'));
      $collection = $thread->collection($user);
      if($collection){
         $collection->keep_updated = !$collection->keep_updated;
         $collection->save();
         return $collection;
      }else{
         return "notwork";
      }
   }

   public function clearupdates()
   {
      $user = Auth::user();
      Collection::where('user_id','=',$user->id)->update(['updated'=> false]);
      Follower::where('follower_id','=',$user->id)->update(['updated'=> false]);
      return 'worked';
   }

   public function books()
   {

      $user = Auth::user();
      $books = DB::table('collections')
         ->join('threads','collections.thread_id','=','threads.id')
         ->join('books','threads.book_id','=','books.id')
         ->join('users','threads.user_id','=','users.id')
         ->join('labels','threads.label_id','=','labels.id')
         ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
         ->where([['collections.user_id','=',$user->id], ['threads.deleted_at', '=', null],['threads.book_id','>',0]])
         ->select('books.*','threads.*','users.name','labels.labelname', 'chapters.title as last_chapter_title','chapters.responded as last_chapter_responded', 'collections.updated as updated','collections.keep_updated as keep_updated')
         ->orderBy('books.lastaddedchapter_at','desc')
         ->paginate(config('constants.index_per_page'));
      $book_info = config('constants.book_info');
      $active = 0;
      $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated];
      $collections = true;
      Auth::user()->collection_books_updated = 0;
      Auth::user()->save();
      return view('users.collections_books', compact('books', 'book_info', 'active','updates','collections'))->with('show_as_collections',1);
   }
   public function threads()
   {
      $user = Auth::user();
      $threads = DB::table('collections')
         ->join('threads','collections.thread_id','=','threads.id')
         ->join('users','threads.user_id','=','users.id')
         ->join('labels','threads.label_id','=','labels.id')
         ->join('channels','threads.channel_id','=','channels.id')
         ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
         ->where([['collections.user_id','=',$user->id], ['threads.deleted_at', '=', null],['threads.book_id','=',0]])
         ->select('threads.*','users.name','labels.labelname', 'posts.body as last_post_body','channels.channelname','collections.updated as updated','collections.keep_updated as keep_updated')
         ->orderBy('threads.lastresponded_at','desc')
         ->paginate(config('constants.items_per_page'));
      $updates = [$user->collection_books_updated,$user->collection_threads_updated,$user->collection_statuses_updated];
      $user->collection_threads_updated = 0;
      $user->save();
      return view('users.collections_threads', compact('threads','updates'))->with('show_as_collections',1)->with('active',1);
   }

   public function statuses()
   {
      $user = Auth::user();
      $statuses = DB::table('followers')
         ->join('users','followers.user_id','=','users.id')
         ->join('statuses','users.id','=','statuses.user_id')
         ->where([['followers.follower_id','=',$user->id], ['users.deleted_at', '=', null]])
         ->select('statuses.*','users.name','followers.keep_updated as keep_updated','followers.updated as updated')
         ->orderBy('statuses.created_at','desc')
         ->paginate(config('constants.index_per_page'));
      $active = 2;
      //dd($statuses);
      $updates = [Auth::user()->collection_books_updated,Auth::user()->collection_threads_updated,Auth::user()->collection_statuses_updated];
      $collections = true;
      Auth::user()->collection_statuses_updated = 0;
      Auth::user()->save();
      return view('users.collections_statuses', compact('statuses','user','active','updates','collections'))->with('show_as_collections',1);
   }
}
