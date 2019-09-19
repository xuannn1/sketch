<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use CacheUser;
use ConstantObjects;

use App\Sosadfun\Traits\TitleObjectTraits;

class TitleController extends Controller
{
    use TitleObjectTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['index'],
        ]);
        $this->middleware('admin')->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titles = \App\Models\Title::all();
        return view('titles.index', compact('titles'));
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
    public function show($id)
    {
        //
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

    public function mytitles()
    {
        $user = CacheUser::Auser();
        $info = CacheUser::Ainfo();
        if(!$user||!$info){abort(404);}
        $level_titles = ConstantObjects::title_type('level');
        $user_titles = $user->titles;
        return view('titles.mytitles',compact('user','level_titles','user_titles'));
    }

    public function wear($title)
    {
        $user = CacheUser::Auser();
        if(!$user){abort(404);}
        if(!is_numeric($title)){abort(409,'名称不合理');}
        if(!$user->hasTitle($title)){
            return redirect()->back()->with('warning','你不具有这个头衔，无法佩戴');
        }
        $user->update(['title_id'=>$title]);
        CacheUser::clearuser($user->id);
        return redirect()->back()->with('success','你已成功更换头衔！');
    }
}
