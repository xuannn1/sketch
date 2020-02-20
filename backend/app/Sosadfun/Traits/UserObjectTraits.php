<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;
use App\Models\Thread;
use App\Models\Post;

trait UserObjectTraits{

    public function select_user_comments($include_anonymous, $include_bianyuan, $id, $request)
    {
        if ($include_anonymous && $include_bianyuan) {
            $posts = Post::join('threads', 'threads.id', '=', 'posts.thread_id')
            ->withUser($id)
            ->where('threads.deleted_at', '=', null)
            ->withType(['post', 'comment'])
            ->ordered('latest_created')
            ->select('posts.*')
            ->paginate(config('preference.posts_per_page'));
        } else {
            $queryid = 'UserComment.'
            .url('/')
            .$id
            .'include_bianyuan'.$include_anonymous
            .'include_anonymous'.$include_bianyuan
            .(is_numeric($request->page)? 'P'.$request->page:'P1');

            $posts = Cache::remember($queryid, 10, function () use($request, $id) {
                return $posts = Post::join('threads', 'threads.id', '=', 'posts.thread_id')
                ->userOnly($id)
                ->where('threads.deleted_at', '=', null)
                ->isPublic()
                ->inPublicChannel()
                ->withType(['post', 'comment'])
                ->withFolded()
                ->ordered('latest_created')
                ->select('posts.*')
                ->paginate(config('preference.posts_per_page'))
                ->appends($request->only('page'));
            });
        }

        $posts->load('simpleThread');
        return $posts;
    }

    public function select_user_threads($include_anonymous, $include_unpublic, $include_bianyuan, $is_book, $id, $request) {

        if ($include_anonymous && $include_unpublic) {
            $query = Thread::with('tags','author','last_post')
            ->withUser($id);

            $data = $this->query_filter($query, $is_book);
        } else {
            $queryid = 'User'.($is_book ? 'Book.' : 'Thread.')
            .url('/')
            .$id
            .'include_anonymous'.$include_anonymous
            .'$include_unpublic'.$include_unpublic
            .'include_bianyuan'.$include_bianyuan
            .(is_numeric($request->page)? 'P'.$request->page:'P1');
            // TODO 可以适当简化query id

            $data = Cache::remember($queryid, 10, function () use($include_bianyuan, $is_book, $request, $id) {
                $query = Thread::with('tags','author','last_post')
                ->withUser($id)
                ->isPublic()
                ->inPublicChannel()
                ->withAnonymous('none_anonymous_only');

                if (!$include_bianyuan) {
                    $query->withBianyuan();
                }

                $query = $this->query_filter($query, $is_book)
                ->appends($request->only('page'));

                return $query;
            });
        }

        return $data;
    }

    private function query_filter($query, $is_book) {
        if ($is_book) {
            $query->withType('book')->ordered('latest_add_component');
        } else {
            $query->withoutType('book')->ordered();
        }

        return $query->paginate(config('preference.threads_per_page'));
    }
}
