<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Thread;
use Carbon;
use DB;
use Auth;
use CacheUser;

class CollectionsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
    }


    public function store(Request $request)
    {
        // default input: [
            // 'thread_id' => xxx,
            // 'group' => xxx,
        //];
        $thread = $this->findThread(request('thread_id'));
            // TODO: test if user can see this thread
        if(!$thread->isCollectedBy(Auth::id())){
            Collection::create([
                'thread_id' => request('thread_id'),
                'group' => request('group')??0,
            ]);
        }


    }

    public function cancel(Request $request)
    {

    }
    public function togglekeepupdate(Request $request)
    {
        $thread = Thread::find(request('thread_id'));
        $collection = $thread->collection(Auth::id());
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




}
