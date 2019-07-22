<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Cache;
use Auth;
use ConstantObjects;
use Carbon;
use CacheUser;

use App\Sosadfun\Traits\AdministrationTraits;
use App\Sosadfun\Traits\PageObjectTraits;

class PagesController extends Controller
{
    use AdministrationTraits;
    use PageObjectTraits;

    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['search', 'self_adminnistrationrecords','create_thread_entry'],
        ]);
    }

    public function home()
    {
        $quotes = $this->quotes();
        $short_recom = $this->short_recommendations();
        $thread_recom = $this->thread_recommendation();
        $channels = ConstantObjects::allChannels();
        $channel_threads = [];
        foreach($channels as $channel){
            if($channel->is_public||(Auth::check()&&Auth::user()->canSeeChannel($channel->id))){
                $channel_threads[$channel->id] = [
                    'channel' => $channel,
                    'threads' => $this->channel_threads($channel->id)
                ];
            }
        }
        return view('pages/home',compact('quotes','short_recom','thread_recom','channel_threads'));
    }
    public function about()
    {
        return view('pages/about');
    }

    public function test()
    {
        return view('pages/test');
    }

    public function error($error_code)
    {
        $errors = array(
            "401" => "抱歉，您未登陆",
            "403" => "抱歉，由于设置，您无权限访问该页面",
            "404" => "抱歉，该页面不存在或已删除",
            "405" => "抱歉，数据库不支持本操作",//修改或增添
            "409" => "抱歉，数据冲突。",
        );
        $error_message = $errors[$error_code];
        return view('errors.errorpage', compact('error_message'));
    }
    public function administrationrecords(Request $request)
    {
        $user_id = 0;
        $user_name = null;
        $page = is_numeric($request->page)? $request->page:'1';

        if(Auth::check()&&$request->user_id==Auth::id()){
            $user_id = Auth::id();
        }
        if(Auth::check()&&Auth::user()->isAdmin()&&$request->user_id>0){
            $user_id = $request->user_id;
        }

        if($user_id>0){
            CacheUser::Ainfo()->clear_column('administration_reminders');
            $records = $this->findAdminRecords($user_id, $page);
            $user_name = CacheUser::user($user_id)->name;
        }else{
            $records = Cache::remember('adminrecords-p'.$page, 5, function () use($page) {
                return $this->findAdminRecords(0, $page);
            });
        }

        return view('pages.adminrecords',compact('records', 'user_name'));
    }


    public function contacts()
    {
        return view('pages.contacts');
    }

    public function recommend_records(Request $request)
    {
        $page = is_numeric($request->page)? $request->page:'1';
        $short_reviews = Cache::remember('recommendation_indexes'.$page, 1, function () use($request) {
            $short_reviews = \App\Models\Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
            ->reviewRecommend('recommend_only')
            ->reviewEditor('editor_only')
            ->reviewLong('short_only')
            ->reviewOrdered('latest_created')
            ->select('posts.*')
            ->paginate(config('preference.items_per_page'))
            ->appends($request->only('page'));
            $short_reviews->load('review.reviewee.author');
            return $short_reviews;
        });
        return view('reviews.index',compact('short_reviews'));
    }

    public function create_thread_entry()
    {
        $user = CacheUser::Auser();

        return view('pages.create_thread_entry', compact('user'));

    }

    public function all_tags()
    {
        $level = 0;
        if(Auth::check()){$level = Auth::user()->level;}

        $tag_range = ConstantObjects::organizeBasicBookTags();

        return view('pages.all_tags', compact('tag_range','level'));
    }

    public function search()
    {

    }
}
