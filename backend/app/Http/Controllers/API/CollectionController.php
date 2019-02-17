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

    public function index(Request $request)//显示自己的收藏内容, 默认显示图书收藏
    {
        $user_id = $request->user_id&&auth('api')->user()->inRole('admin')?$request->user_id:auth('api')->id();//除非是管理员，否则不能任意设定看谁的收藏夹

        $threads = Thread::with('author','tags', 'last_component', 'last_post')
        ->join('collections', 'threads.id','=','collections.thread_id')
        ->withType($request->withType??'book')
        ->where('collections.user_id', $user_id)
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
