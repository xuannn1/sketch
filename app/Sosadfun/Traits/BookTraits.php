<?php
namespace App\Sosadfun\Traits;

use Illuminate\Support\Facades\DB;

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
}
