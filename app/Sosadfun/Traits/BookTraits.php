<?php
namespace App\Sosadfun\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Models\Label;
use App\Models\Tag;
use Carbon\Carbon;

use Auth;

trait BookTraits{
    public function join_book_tables(){
        $query = DB::table('threads')
        ->join('books', 'threads.book_id', '=', 'books.id')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->join('labels', 'threads.label_id', '=', 'labels.id')
        ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
        ->leftjoin('tongrens','books.id','=', 'tongrens.book_id')
        ->leftjoin('tags as tongren_yuanzhu_tags','tongren_yuanzhu_tags.id','=', 'tongrens.tongren_yuanzhu_tag_id')
        ->leftjoin('tags as tongren_cp_tags','tongren_cp_tags.id','=', 'tongrens.tongren_cp_tag_id');
        return $query;
    }

    public function return_book_fields($query){
        $query->select('books.*', 'threads.*', 'tongrens.tongren_yuanzhu','tongrens.tongren_cp','tongrens.tongren_yuanzhu_tag_id','tongrens.tongren_cp_tag_id', 'users.name','labels.labelname', 'chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded', 'chapters.post_id as last_chapter_post_id','tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname','tongren_cp_tags.tagname as tongren_cp_tagname');
        return $query;
    }

    public function all_book_tags(){
        $tags=[];
        $remember = 30;
        $tags['labels_yuanchuang']= Cache::remember('-labels_yuanchuang', $remember, function () {
            $labels_yuanchuang = Label::where('channel_id',1)
            ->get();
            return $labels_yuanchuang;
        });
        $tags['labels_tongren']=Cache::remember('-labels_tongren', $remember, function () {
            $labels_tongren = Label::where('channel_id',2)
            ->get();
            return $labels_tongren;
        });
        $tags['tags_feibianyuan']=Cache::remember('-tags-feibianyuan', $remember, function () {
            $tags_feibianyuan = Tag::where('tag_group',0)
            ->get();
            return $tags_feibianyuan;
        });
        $tags['tags_bianyuan']=Cache::remember('-tags-bianyuan', $remember, function () {
            $tags_bianyuan = Tag::where('tag_group',5)
            ->get();
            return $tags_bianyuan;
        });
        $tags['tags_tongren']=Cache::remember('-tags-tongren', $remember, function () {
            $tags_tongren = Tag::where('tag_group',25)
            ->get();
            return $tags_tongren;
        });
        $tags['tags_tongren_yuanzhu']=Cache::remember('-tags-tongren-yuanzhu', $remember, function () {
            $tags_tongren_yuanzhu = Tag::where('tag_group',10)
            ->get();
            return $tags_tongren_yuanzhu;
        });
        $tags['tags_tongren_cp']=Cache::remember('-tags-tongren-cp', $remember, function () {
            $tags_tongren_cp = Tag::where('tag_group',20)
            ->get();
            return $tags_tongren_cp;
        });
        return $tags;
    }
}
