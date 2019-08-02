<?php
namespace App\Sosadfun\Traits;

use DB;
use Cache;
use ConstantObjects;
use Auth;
use App\Models\Thread;
use StringProcess;

trait ThreadQueryTraits{

    public function jinghua_threads()
    {
        return Cache::remember('jinghua-threads', 10, function () {
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
        $selectors = ['inChannel', 'isPublic', 'inPublicChannel', 'withType', 'withBianyuan', 'withTag', 'excludeTag', 'ordered', 'page'];
        foreach($selectors as $selector){
            if(array_key_exists($selector, $request_data)){
                $queryid.='-'.$selector.':'.$request_data[$selector];
            }
        }
        return $queryid;
    }

    public function sanitize_request_data($request)
    {
        $request_data = $request->only('inChannel', 'isPublic', 'inPublicChannel',  'withType', 'withBianyuan', 'withTag', 'excludeTag', 'ordered', 'page');
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
        return Cache::remember('ThreadQ.'.$query_id, 5, function () use($request_data) {
            return Thread::with('author', 'tags', 'last_component', 'last_post')
            ->inChannel(array_key_exists('inChannel',$request_data)? $request_data['inChannel']:'')
            ->isPublic(array_key_exists('isPublic',$request_data)? $request_data['isPublic']:'')
            ->inPublicChannel(array_key_exists('inPublicChannel',$request_data)? $request_data['inPublicChannel']:'')
            ->withType(array_key_exists('withType',$request_data)? $request_data['withType']:'')
            ->withBianyuan(array_key_exists('withBianyuan',$request_data)? $request_data['withBianyuan']:'') //
            ->withTag(array_key_exists('withTag',$request_data)? $request_data['withTag']:'')
            ->excludeTag(array_key_exists('excludeTag',$request_data)? $request_data['excludeTag']:'')
            ->ordered(array_key_exists('ordered',$request_data)? $request_data['ordered']:'latest_add_component')
            ->paginate(config('preference.threads_per_page'))
            ->appends($request_data);
        });
    }

    public function find_books_with_query($query_id, $request_data)
    {
        return Cache::remember('BookQ.'.$query_id, 5, function () use($request_data) {
            $threads = Thread::with('author', 'tags', 'last_component', 'last_post')
            ->isPublic()
            ->withType('book')
            ->inChannel(array_key_exists('inChannel',$request_data)? $request_data['inChannel']:'')
            ->withBianyuan(array_key_exists('withBianyuan',$request_data)? $request_data['withBianyuan']:'') //
            ->withTag(array_key_exists('withTag',$request_data)? $request_data['withTag']:'')
            ->excludeTag(array_key_exists('excludeTag',$request_data)? $request_data['excludeTag']:'')
            ->ordered(array_key_exists('ordered',$request_data)? $request_data['ordered']:'latest_add_component')
            ->paginate(config('preference.threads_per_page'))
            ->appends($request_data);
            $channels = ConstantObjects::find_channels_by_inChannel(array_key_exists('inChannel',$request_data)? $request_data['inChannel']:'');
            $selected_tags = ConstantObjects::find_tags_by_withTag(array_key_exists('withTag',$request_data)? $request_data['withTag']:'');
            $excluded_tags = ConstantObjects::find_tags_by_excludeTag(array_key_exists('excludeTag',$request_data)? $request_data['excludeTag']:'');
            return[
                'threads' => $threads,
                'selected_tags' => $selected_tags,
                'excluded_tags' => $excluded_tags,
                'channels' => $channels,
            ];
        });
    }

    public function convert_book_request_data($request)
    {
        $request_data = $request->only('withBianyuan', 'ordered');
        $withTag='';
        if($request->channel_id){
            $inChannel=StringProcess::concatenate_channels($request->channel_id);
        }
        if($request->book_length_tag){
            $withTag=StringProcess::concatenate_andTag($request->book_length_tag, $withTag);
        }
        if($request->book_status_tag){
            $withTag=StringProcess::concatenate_andTag($request->book_status_tag, $withTag);
        }
        if($request->sexual_orientation_tag){
            $withTag=StringProcess::concatenate_andTag($request->sexual_orientation_tag, $withTag);
        }
        if($request->withTag){
            $withTag=StringProcess::concatenate_andTag($request->withTag, $withTag);
        }
        if($withTag){
            $request_data = array_merge(['withTag'=>$withTag],$request_data);
        }
        $excludeTag='';
        if($request->excludeTag){
            $excludeTag=StringProcess::concatenate_excludeTag($request->excludeTag, $excludeTag);
        }
        if($excludeTag){
            $request_data = array_merge(['excludeTag'=>$excludeTag],$request_data);
        }
        if($inChannel){
            $request_data = array_merge(['inChannel'=>$inChannel],$request_data);
        }
        return $request_data;
    }
}
