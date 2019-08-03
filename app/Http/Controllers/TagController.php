<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Tag;
use ConstantObjects;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->except('all_tags','index','show');
    }

    public function all_tags()
    {
        $level = 0;
        if(Auth::check()){$level = Auth::user()->level;}
        $tag_range = ConstantObjects::organizeBookSelectorTags();
        return view('tags.all_tags', compact('tag_range','level'));
    }

    public function index()
    {
        $tag_range = ConstantObjects::organizeBookSelectorTags();
        return view('tags.index', compact('tag_range'));
    }

    public function create(Request $request)
    {
        $data = $request->only('tag_type','channel_id','parent_id');
        return view('tags.create', compact('data'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'tag_name' => 'required|string|max:10|unique:tags',
            'tag_explanation' => 'nullable|string|max:190',
            'is_bianyuan' => 'required|string',
            'is_primary' => 'required|string',
            'channel_id' => 'required|numeric',
            'parent_id' => 'required|numeric',
        ]);
        $tag_data=$request->only('tag_name','tag_explanation','channel_id','parent_id');
        $tag_data['is_bianyuan'] = $request->is_bianyuan=='isnot'? false:true;
        $tag_data['is_primary'] = $request->is_primary=='isnot'? false:true;
        $tag = Tag::create($tag_data);
        ConstantObjects::refreshBookTags();
        if($tag->parent_id>0){
            ConstantObjects::refreshTagProfile($tag->parent_id);
        }
        return redirect()->route('tag.show', $tag->id);

    }
    public function show($id)
    {
        $tag = ConstantObjects::findTagProfile($id);
        if(!$tag){abort(404);}
        return view('tags.show', compact('tag'));
    }
    public function edit($id)
    {
        $tag = ConstantObjects::findTagProfile($id);
        if(!$tag){abort(404);}
        return view('tags.edit', compact('tag'));
    }
    public function update(Request $request, $id)
    {
        $tag = ConstantObjects::findTagProfile($id);
        if(!$tag){abort(404);}

        $this->validate($request, [
            'tag_name' => 'required|string|max:10|unique:tags',
            'tag_explanation' => 'nullable|string|max:190',
            'is_bianyuan' => 'required|string',
            'is_primary' => 'required|string',
            'channel_id' => 'required|numeric',
            'parent_id' => 'required|numeric',
        ]);
        $tag_data=$request->only('tag_name','tag_explanation','channel_id','parent_id');
        $tag_data['is_bianyuan'] = $request->is_bianyuan=='isnot'? false:true;
        $tag_data['is_primary'] = $request->is_primary=='isnot'? false:true;
        $tag->update($tag_data);
        ConstantObjects::refreshTagProfile($id);
        return redirect()->route('tag.show', $id);

    }
}
