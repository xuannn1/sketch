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
    public function join_complex_book_tables(){
        $query = DB::table('threads')
        ->join('books', 'threads.book_id', '=', 'books.id')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->join('labels', 'threads.label_id', '=', 'labels.id')
        ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
        ->leftjoin('tongrens','books.id','=', 'tongrens.book_id')
        ->leftjoin('tagging_threads','tagging_threads.thread_id','=', 'threads.id')
        ->leftjoin('tags as tongren_yuanzhu_tags','tongren_yuanzhu_tags.id','=', 'tongrens.tongren_yuanzhu_tag_id')
        ->leftjoin('tags as tongren_cp_tags','tongren_cp_tags.id','=', 'tongrens.tongren_cp_tag_id');
        return $query;
    }

    public function return_book_fields($query){
        $query->select('books.thread_id','books.book_status', 'books.book_length', 'books.lastaddedchapter_at', 'books.total_char', 'books.last_chapter_id', 'books.sexual_orientation',
        'threads.id','threads.user_id','threads.book_id', 'threads.title', 'threads.brief', 'threads.locked', 'threads.public', 'threads.bianyuan', 'threads.anonymous', 'threads.majia', 'threads.noreply', 'threads.viewed', 'threads.responded', 'threads.lastresponded_at', 'threads.channel_id', 'threads.label_id', 'threads.deleted_at', 'threads.created_at', 'threads.edited_at', 'threads.post_id', 'threads.last_post_id', 'threads.downloaded',
        'tongrens.tongren_yuanzhu', 'tongrens.tongren_cp', 'tongrens.tongren_yuanzhu_tag_id', 'tongrens.tongren_cp_tag_id',
        'users.name', 'labels.labelname', 'chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded', 'chapters.post_id as last_chapter_post_id',
        'tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname','tongren_cp_tags.tagname as tongren_cp_tagname');
        return $query;
    }

    public function all_book_tags(){

        $remember = 30;
        $tags =  Cache::remember('tags', $remember, function () {
            $tags=[];
            $tags['labels_yuanchuang'] = $labels_yuanchuang = Label::where('channel_id',1)
            ->get();
            $tags['labels_tongren'] = Label::where('channel_id',2)
            ->get();
            $tags['tags'] = Tag::whereIn('tag_group',[0,5,25])
            ->orderBy('tag_info','asc')
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();
            $tags['tags_tongren_yuanzhu']=Tag::where('tag_group',10)
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();
            return $tags;
        });
        return $tags;

    }
    public function extra_book_tags(){

        $remember = 30;
        $tags =  Cache::remember('ext_tags', $remember, function () {
            $tags=[];
            $tags['labels_yuanchuang'] = $labels_yuanchuang = Label::where('channel_id',1)
            ->get();
            $tags['labels_tongren'] = Label::where('channel_id',2)
            ->get();
            $tags['tags_feibianyuan'] = Tag::where('tag_group',0)
            ->orderBy('tag_info','asc')
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();
            $tags['tags_bianyuan'] = Tag::where('tag_group',5)
            ->orderBy('tag_info','asc')
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();
            $tags['tags_tongren'] = Tag::where('tag_group',25)
            ->orderBy('tag_info','asc')
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();
            $tags['tags_tongren_yuanzhu']=Tag::where('tag_group',10)
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();
            $tags['tags_tongren_cp']= Tag::where('tag_group',20)
            ->orderBy('books','desc')
            ->select('id','tagname','tag_explanation','tag_group','tag_info','books')
            ->get();

            return $tags;
        });
        return $tags;
    }

}
