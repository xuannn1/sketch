<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;

trait UserObjectTraits{

    public function select_user_comments($include_anonymous=0, $include_bianyuan=0, $id, $request)
    {
        $queryid = 'UserComment.'
        .url('/')
        .$id
        .'include_bianyuan'.$include_anonymous
        .'include_anonymous'.$include_bianyuan
        .(is_numeric($request->page)? 'P'.$request->page:'P1');

        $posts = Cache::remember($queryid, 10, function () use($request, $id, $include_anonymous, $include_bianyuan) {
            $query = \App\Models\Post::join('threads','threads.id','=','posts.thread_id')
            ->whereIn('threads.channel_id',ConstantObjects::public_channels())
            ->where('threads.deleted_at','=',null)
            ->whereIn('posts.type',['post','comment']);
            if(!$include_anonymous){
                $query->where('posts.is_anonymous','=',0);
            }
            if(!$include_bianyuan){
                $query->where('posts.is_bianyuan','=',0);
            }
            $posts = $query->where('posts.user_id','=',$id)
            ->orderBy('posts.created_at','desc')
            ->select('posts.*')
            ->paginate(config('preference.posts_per_page'))
            ->appends($request->only('page'));
            $posts->load('simpleThread');
            return $posts;
        });
        return $posts;
    }
}
