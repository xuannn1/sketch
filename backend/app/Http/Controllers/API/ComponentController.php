<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Post;
use App\Models\PostInfo;
use CacheUser;
use Auth;
use Carbon;
use StringProcess;
use App\Sosadfun\Traits\ThreadObjectTraits;
use App\Sosadfun\Traits\PostObjectTraits;


class ComponentController extends Controller
{
    use ThreadObjectTraits;
    use PostObjectTraits;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function update_component_index($id, Request $request)
    {
        $thread = Thread::find($id);
        $user = auth('api')->user();
        if(!$thread){abort(404);}
        if($thread->user_id!=$user->id||($thread->is_locked&&!$user->isAdmin())){abort(403);}

        $posts = $thread->component_index();

        foreach($request->order_by as $key=>$order_by){
            if(is_numeric($order_by)){
                $post = $posts->firstWhere('id', $key);
                $post->info->update(['order_by' => $order_by]);
            }
        }
        $thread->reorder_components();

        $first = $request->first_component_id;
        if($first&&is_numeric($first)){
            $post = $posts->firstWhere('id', $first);
            if($post&&$post->user_id===$user->id&&$post->type==='chapter'){
                $thread->update(['first_component_id'=>$first]);
            }
        }
        $last = $request->last_component_id;
        if($last&&is_numeric($last)){
            $post = $posts->firstWhere('id', $last);
            if($post&&$post->user_id===$user->id&&$post->type==='chapter'){
                $thread->update(['last_component_id'=>$last]);
            }
        }

        $this->clearThread($id);
        $thread = $this->threadProfile($id);

        return response()->success([
            'thread' => new ThreadProfileResource($thread),
        ]);
    }

    public function convert($id, Request $request){
        $post = Post::find($id);
        if(!$post){abort(404);}

        $thread=$post->thread;
        if($thread->is_locked||$thread->user_id!=Auth::id()){abort(403);}

        //validate turn to type and channel type

        // $post = ?
        // $request->convert_to_type ==='post'//'comment'...
        // TODO post to chapter, chapter to post, and so on.
    }

}
