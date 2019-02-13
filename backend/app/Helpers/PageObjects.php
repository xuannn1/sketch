<?php

namespace App\Helpers;
use App\Models\Quote;
use App\Models\Thread;
use App\Models\Status;
use App\Models\Post;
use App\Helpers\ConstantObjects;
use Cache;

class PageObjects
{
    public static function recent_quotes()//在首页上显示的最新quotes
    {
        return Cache::remember('recent_quotes', 1, function () {
            return Quote::where('is_approved', true)
            ->with('author')
            ->inRandomOrder()
            ->limit(config('constants.quotes_on_homepage'))
            ->get();
        });
    }
    public static function recent_added_chapter_books()//在首页上显示的最新更新章节的图书
    {
        return Cache::remember('recent_added_chapter_books', 1, function () {
            return Thread::threadBrief()
            ->isPublic()
            ->withType('book')
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('add_component_at')
            ->take(config('constants.books_on_homepage'))
            ->get();
        });
    }
    public static function recent_responded_books()//在首页上显示的最新更新章节的图书
    {
        return Cache::remember('recent_responded_books', 1, function () {
            return Thread::threadBrief()
            ->isPublic()
            ->withType('book')
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('responded_at')
            ->take(config('constants.books_on_homepage'))
            ->get();
        });
    }

    public static function highest_jifen_books()//在首页上显示的积分最高的图书
    {
        return Cache::remember('highest_jifen_books', 1, function () {
            return Thread::threadBrief()
            ->isPublic()
            ->withType('book')
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('jifen')
            ->take(config('constants.books_on_homepage'))
            ->get();
        });
    }

    public static function most_collected_books()//在首页上显示的积分最高的图书
    {
        return Cache::remember('most_collected_books', 1, function () {
            return Thread::threadBrief()
            ->isPublic()
            ->withType('book')
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('collection_count')
            ->take(config('constants.books_on_homepage'))
            ->get();
        });
    }

    public static function recent_responded_threads()//在首页上显示的最新回复过的讨论帖
    {
        return Cache::remember('recent_responded_threads', 1, function () {
            return Thread::threadBrief()
            ->isPublic()
            ->withType('thread')
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('responded_at')
            ->take(config('constants.threads_on_homepage'))
            ->get();
        });
    }

    public static function digested_threads()//在首页上显示的带精华tag的讨论帖
    {
        $tag = ConstantObjects::allTags()->keyby('tag_name')->get('精华');
        return Cache::remember('digested_threads', 1, function () use($tag) {
            return Thread::threadBrief()
            ->isPublic()
            ->withType('thread')
            ->withTag($tag->id)
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('responded_at')
            ->take(config('constants.threads_on_homepage'))
            ->get();
        });
    }
    public static function recent_statuses()//在首页上显示的最新状态
    {
        return Cache::remember('recent_statuses', 1, function () {
            return Status::where('attachable_id', 0)
            ->where('reply_id', 0)
            ->orderBy('created_at', 'desc')
            ->take(config('constants.statuses_on_homepage'))
            ->get();
        });
    }
    public static function recent_short_recommendations()//在首页显示书籍推荐，还需要结合产品的要求来实现。
    //以前是最新+随机长评，以后有了recommendation检索功能，可以不需要那么随机了，需要的人自己进去随机就好。前端可以提供随机排序方式
    {
        return Cache::remember('recent_short_recommendations', 1, function () {
            $short_reviews = Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('editor_only')
            ->reviewLong('short_only')
            ->reviewOrdered('latest_created')
            ->select('posts.*')
            ->take(config('constants.short_recommendations_on_homepage'))
            ->get();
            $short_reviews->load('review.reviewee');
            return $short_reviews;
        });
    }

    public static function random_short_recommendations()//在文库首页显示随机的往期短推
    {
        return Cache::remember('random_short_recommendations', 1, function () {
            $short_reviews = Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('editor_only')
            ->reviewLong('short_only')
            ->reviewOrdered('random')
            ->select('posts.*')
            ->take(config('constants.short_recommendations_on_homepage'))
            ->get();
            $short_reviews->load('review.reviewee');
            return $short_reviews;
        });
    }

    public static function recent_long_recommendations()
    {
        return Cache::remember('recent_long_recommendations', 1, function () {
            $long_reviews = Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('editor_only')
            ->reviewLong('long_only')
            ->reviewOrdered('latest_created')
            ->select('posts.*')
            ->take(config('constants.long_recommendations_on_homepage'))
            ->get();
            $long_reviews->load('review.reviewee');
            return $long_reviews;
        });
    }

    public static function recent_custom_short_recommendations()//在文库首页显示新的用户书评
    {
        return Cache::remember('recent_custom_shotr_recommendations', 1, function () {
            $short_reviews = Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('none_editor_only')
            ->reviewLong('short_only')
            ->reviewOrdered('latest_created')
            ->select('posts.*')
            ->take(config('constants.short_recommendations_on_homepage'))
            ->get();
            $short_reviews->load('review.reviewee');
            return $short_reviews;
        });
    }

    public static function recent_custom_long_recommendations()//在文库首页显示新的用户书评
    {
        return Cache::remember('recent_custom_long_recommendations', 1, function () {
            $long_reviews = Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('none_editor_only')
            ->reviewLong('long_only')
            ->reviewOrdered('latest_created')
            ->select('posts.*')
            ->take(config('constants.long_recommendations_on_homepage'))
            ->get();
            $long_reviews->load('review.reviewee');
            return $long_reviews;
        });
    }

    public static function recent_QAs()
    {
        return Cache::remember('recent_QAs', 1, function () {
            $recent_QAs =  Post::withType('answer')
            ->take(config('constants.QAs_on_homepage'))
            ->get();
            $recent_QAs->load('parent', 'author');
            return $recent_QAs;
        });
    }

    public static function channel_threads($channel_id)
    {
        return Cache::remember('channel_threads_ch'.$channel_id, 1, function () use($channel_id) {
            return Thread::threadBrief()
            ->isPublic()
            ->inChannel($channel_id)
            ->withBianyuan('none_bianyuan_only')
            ->with('author', 'tags')
            ->ordered('responded_at')
            ->take(config('constants.threads_per_channel'))
            ->get();
        });
    }

}
