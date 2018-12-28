<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Chapter;
use App\Http\Resources\BookResources\BookProfileResource;
use App\Http\Resources\BookResources\ChaptersResource;

class BookController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('filter_thread')->only('show');
    }

    public function index(Request $request)
    {
        $books = Thread::threadInfo()
        ->inChannel($request->channel)
        ->isPublic()
        ->withTag($request->tag)
        ->with('author','tags')
        ->orderBy($request->orderBy)
        ->paginate(config('constants.threads_per_page'));
        return response()->success(new ThreadsResource($threads));
        //return view('test',compact('threads'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($thread)
    {
        $thread = Thread::find($thread);
        if($thread){
            $thread->load('author', 'tags');
            $chapters = Chapter::where('thread_id',$thread)
            ->with('mainpost','volumn')
            ->orderBy('order_by','asc')
            ->paginate(config('constants.chapters_per_page'));
            return response()->success([
                'book' => new BookProfileResource($thread),
                'chapters' => new ChaptersResource($chapters),
            ]);
        }else{
            return response()->error(config('error.404'), 404);
        }
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        //
    }
}
