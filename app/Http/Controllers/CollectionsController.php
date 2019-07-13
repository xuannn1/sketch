<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Thread;
use Carbon;
use DB;
use Auth;
use CacheUser;
use App\Sosadfun\Traits\CollectionObjectTraits;
use App\Sosadfun\Traits\FindThreadTrait;

class CollectionsController extends Controller
{
    use CollectionObjectTraits;
    use FindThreadTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();

        $group = (int)$request->group??0;
        $collections = Collection::where('user_id', $user->id)
        ->where('group',$request->group)
        ->with('thread.author','thread.tags')
        ->paginate(config('preference.threads_per_page'));

        $groups = $this->findCollectionGroups($user->id);

        return view('collections.index',compact('user','info','threads'))->with(['show_collection_tab'=>$request->group??'default']);
    }


    public function store()
    {
        // default input: [
            // 'thread' => xxx,
            // 'group' => xxx,
        //];

        if($this->chechCollectedOrNot(Auth::id(), request('thread'))){
            $collection = Collection::create([
                'thread_id' => request('thread'),
                'group' => request('group')??0,
            ]);
            return [
                'success' => '您已成功收藏本文!',
                'collection'=> $collection,
            ];
        }
        return [
            'info' => '您已收藏过本文,请勿重复收藏!',
        ];

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
