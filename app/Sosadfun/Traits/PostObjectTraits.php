<?php
namespace App\Sosadfun\Traits;

use Cache;

trait PostObjectTraits{

    public function postProfile($id)
    {
        return Cache::remember('post.'.$id, 15, function () use($id) {
            $post = \App\Models\Post::find($id);
            if(!$post){
                return;
            }
            $post->load('author.title','tags');
            if($post&&$post->type==='chapter'){
                $post->load('chapter');
            }
            if($post&&$post->type==='review'){
                $post->load('review.reviewee');
            }
            if($post&&$post->type==='question'){
                $post->load('answers');
            }
            if($post&&$post->type==='answer'){
                $post->load('question');
            }
            $post->setAttribute('top_reply', $post->favorite_reply());
            $post->setAttribute('new_reply', $post->newest_reply());
            $post->setAttribute('recent_rewards', $post->latest_rewards());
            $post->setAttribute('recent_upvotes', $post->latest_upvotes());

            return $post;
        });
    }

}
