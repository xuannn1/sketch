<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;
use Auth;
use App\Models\Thread;

trait ThreadQueryTraits{

    public function jinghua_threads()
    {
        return Cache::remember('jinghua-threads', 5, function () {
            $jinghua_tag = ConstantObjects::find_tag_by_name('精华');
            return \App\Models\Thread::with('author','tags')
            ->isPublic()
            ->inPublicChannel()
            ->withTag($jinghua_tag->id)
            ->inRandomOrder()
            ->take(3)
            ->get();
        });
    }

    public function find_top_threads_in_channel($id)
    {
        return Cache::remember('top_threads_in_channel.'.$id, 30, function () use($id) {
            $zhiding_tag = ConstantObjects::find_tag_by_name('置顶');
            return \App\Models\Thread::with('author','tags')
            ->inChannel($id)
            ->withTag($zhiding_tag->id)
            ->get();
        });
    }

    public function process_thread_query_id($request_data)
    {
        $queryid = url('/');
        $selectors = ['inChannel', 'isPublic', 'withType', 'withBianyuan', 'withTag', 'excludeTag', 'ordered', 'page'];
        foreach($selectors as $selector){
            if(array_key_exists($selector, $request_data)){
                $queryid.='-'.$selector.$request_data[$selector];
            }
        }
        return $queryid;
    }

    public function sanitize_request_data($request_data)
    {
        if(!Auth::check()||!Auth::user()->isAdmin()){
            $request_data['isPublic']='';
            $request_data['inPublicChannel']='';
        }
        if(!Auth::check()||Auth::user()->level<3){
            $request_data['withBianyuan']='';
        }
        return $request_data;
    }
    public function find_threads_with_query($query_id, $request_data)
    {
        return Cache::remember('ThreadQ.'.$query_id, 1, function () use($request_data) {
            return Thread::with('author', 'tags', 'last_component', 'last_post')
            ->inChannel(array_key_exists('inChannel',$request_data)? $request_data['inChannel']:'')
            ->isPublic(array_key_exists('isPublic',$request_data)? $request_data['isPublic']:'')
            ->inPublicChannel(array_key_exists('inPublicChannel',$request_data)? $request_data['inPublicChannel']:'')
            ->withType(array_key_exists('withType',$request_data)? $request_data['withType']:'')
            ->withBianyuan(array_key_exists('withBianyuan',$request_data)? $request_data['withBianyuan']:'') //
            ->withTag(array_key_exists('withTag',$request_data)? $request_data['withTag']:'')
            ->excludeTag(array_key_exists('excludeTag',$request_data)? $request_data['excludeTag']:'')
            ->ordered(array_key_exists('ordered',$request_data)? $request_data['ordered']:'')
            ->paginate(config('preference.threads_per_page'))
            ->appends($request_data);
        });
    }
}
