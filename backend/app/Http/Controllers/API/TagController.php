<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;
use App\Models\Tag;
use ConstantObjects;
use App\Http\Resources\TagResource;
use App\Http\Resources\TagInfoResource;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin')->except('index', 'show');
    }

    public function index()
    {
        $tag_range = ConstantObjects::organizeBookSelectorTags();
        return response()->success($tag_range);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tag_name' => 'required|string|max:10|unique:tags',
            'tag_explanation' => 'nullable|string|max:190',
            'tag_type' => 'required|string|max:10',
            'is_bianyuan' => 'required|boolean',
            'is_primary' => 'required|boolean',
            'channel_id' => 'required|numeric',
            'parent_id' => 'required|numeric',
        ]);
        $tag_data = $request->only('tag_name', 'tag_explanation', 'tag_type', 'is_bianyuan', 'is_primary', 'channel_id', 'parent_id');
        $tag = Tag::create($tag_data);
        ConstantObjects::refreshBookTags();
        if ($tag->parent_id > 0) {
            ConstantObjects::refreshTagProfile($tag->parent_id);
        }
        return response()->success(new TagResource($tag));
    }

    public function show($id)
    {
        $tag = ConstantObjects::findTagProfile($id);
        if (!$tag) {
            abort(404);
        }
        return response()->success(new TagResource($tag));
    }

    public function update(Request $request, $id)
    {
        $tag = ConstantObjects::findTagProfile($id);
        if (!$tag) {
            abort(404);
        }

        $this->validate($request, [
            'tag_name' => 'required|string|max:10',
            'tag_explanation' => 'nullable|string|max:190',
            'tag_type' => 'required|string|max:10',
            'is_bianyuan' => 'required|boolean',
            'is_primary' => 'required|boolean',
            'channel_id' => 'required|numeric',
            'parent_id' => 'required|numeric',
        ]);
        $tag_data = $request->only('tag_name', 'tag_explanation', 'tag_type', 'is_bianyuan', 'is_primary', 'channel_id', 'parent_id');

        $tag->update($tag_data);
        ConstantObjects::refreshTagProfile($id);
        return response()->success(new TagResource($tag));
    }

    public function destroy($id)
    {
        $tag = ConstantObjects::findTagProfile($id);
        if (!$tag) {
            abort(404);
        }
        // TODO: 首先解除文库里所有和这个tag关联的thread和post的关联关系
        $tag->threads()->detach();
        // $tag->posts()->detach();

        // TODO：然后删除tag
        $tag->delete();
        ConstantObjects::refreshTagProfile($id);
        return response()->success(['success' => '成功删除该标签']);
    }
}
