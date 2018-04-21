<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use App\Models\Thread;
use App\Models\Post;
use Illuminate\Http\Request;

class PollsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        //
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(Request $request)
    {
        $thread = \App\Models\Thread::find($request->thread);
        return view('polls.poll_create',compact('thread'));
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
    * @param  \App\Models\Poll  $poll
    * @return \Illuminate\Http\Response
    */
    public function show(Poll $poll)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Poll  $poll
    * @return \Illuminate\Http\Response
    */
    public function edit(Poll $poll)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Poll  $poll
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Poll $poll)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Poll  $poll
    * @return \Illuminate\Http\Response
    */
    public function destroy(Poll $poll)
    {
        //
    }
}
