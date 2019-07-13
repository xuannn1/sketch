<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;

trait PageObjectTraits{

    public function quotes()//在首页上显示的最新quotes
    {
        return Cache::remember('quotes', 1, function () {
            $regular_quotes = \App\Models\Quote::where('approved', true)
            ->with('author')
            ->inRandomOrder()
            ->limit(config('preference.regular_quotes_on_homepage'))
            ->get();
            $helper_quotes = \App\Models\Quote::where('approved', true)
            ->with('author')
            ->notSad()
            ->inRandomOrder()
            ->limit(config('preference.helper_quotes_on_homepage'))
            ->get();
            return $regular_quotes->merge($helper_quotes)
            ->shuffle();
        });
    }


    public function short_recommendations()
    {
        return Cache::remember('short_recommendations', 1, function () {
            $short_reviews = \App\Models\Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('editor_only')
            ->reviewLong('short_only')
            ->inRandomOrder()
            ->select('posts.*')
            ->take(config('preference.short_recommendations_on_homepage'))
            ->get();
            $short_reviews->load('review.reviewee');
            return $short_reviews;
        });
    }

    public function thread_recommendation()
    {
        return Cache::remember('thread_recommendation', 1, function () {
            $id = ConstantObjects::system_variable()->homepage_thread_id;
            if($id>0){
                return \App\Models\Thread::find($id);
            }
        });
    }

    public function channel_threads($channel_id)
    {
        return Cache::remember('channel_threads_ch'.$channel_id, 1, function () use($channel_id) {
            return \App\Models\Thread::isPublic()
            ->inChannel($channel_id)
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('responded_at')
            ->take(config('preference.threads_per_channel'))
            ->get();
        });
    }

    public function users_online()
    {
        return Cache::remember('users-online-count', config('constants.online_count_interval'), function () {
        $users_online = DB::table('logging_statuses')
        ->where('logged_on', '>', time()-60*30)
        ->count();
        return $users_online;
        });
    }

    public function web_stat()
    {
        return Cache::remember('webstat-yesterday', config('constants.online_count_interval'), function () {
            $webstat = \App\Models\WebStat::where('id','>',1)->orderBy('created_at', 'desc')->first();
            return $webstat;
        });
    }

}
