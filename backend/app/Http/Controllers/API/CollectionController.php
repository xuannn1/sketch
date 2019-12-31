<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ConstantObjects;

use App\Http\Resources\CollectionResource;
use App\Http\Resources\ThreadCollectionResource;
use App\Http\Resources\PaginateResource;

use App\Models\Thread;
use App\Models\Collection;
use App\Models\User;

class CollectionController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('filter_thread')->only('store');
    }

    public function index(User $user, Request $request)//显示自己的收藏内容, 默认显示图书收藏
    {
        if(!auth('api')->user()->isAdmin()&&($user->id!=auth('api')->id())){abort(403);}

        $threads = Thread::with('author','tags', 'last_component', 'last_post')
        ->join('collections', 'threads.id','=','collections.thread_id')
        ->withType($request->withType??'book')
        ->where('collections.user_id', $user->id)
        ->ordered($request->ordered)
        ->select('threads.id', 'threads.user_id', 'channel_id',  'title',  'is_anonymous', 'majia', 'is_public', 'is_bianyuan', 'collections.is_updated', 'collections.keep_updated', 'collections.id as collection_id')
        ->paginate(config('constants.items_per_page'));

        return response()->success([
            'threads' => ThreadCollectionResource::collection($threads),
            'paginate' => new PaginateResource($threads),
        ]);

    }
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Thread $thread)//
    {
        $collection = Collection::firstOrCreate([
            'user_id' => auth('api')->id(),
            'thread_id' => $thread->id,
        ]);
        return response()->success(new CollectionResource($collection));
    }


    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Collection  $collection
    * @return \Illuminate\Http\Response
    */
    public function update(Collection $collection, Request $request)
    {
        $validatedData = $request->validate([
            'keep_updated' => 'required|boolean',
        ]);
        if($collection->user_id!=auth('api')->id()){abort(403);}
        $collection->update($request->only('keep_updated'));
        return response()->success(new CollectionResource($collection));
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Collection  $collection
    * @return \Illuminate\Http\Response
    */
    public function destroy(Collection $collection)
    {
        if($collection->user_id!=auth('api')->id()){abort(403);}
        $collection->delete();
        return response()->success('deleted');
    }
}
