<?php
namespace App\Sosadfun\Traits;

use Illuminate\Support\Facades\DB;

trait ThreadTraits{
    public function join_thread_tables(){
        $query = DB::table('threads')
        ->join('channels', 'threads.channel_id','=','channels.id')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->join('labels', 'threads.label_id', '=', 'labels.id')
        ->leftjoin('posts','threads.last_post_id','=', 'posts.id')
        ->leftjoin('books', 'threads.book_id', '=', 'books.id')
        ->leftjoin('chapters','books.last_chapter_id','=', 'chapters.id')
        ->leftjoin('tongrens','books.id','=', 'tongrens.book_id')
        ->leftjoin('tags as tongren_yuanzhu_tags','tongren_yuanzhu_tags.id','=', 'tongrens.tongren_yuanzhu_tag_id')
        ->leftjoin('tags as tongren_cp_tags','tongren_cp_tags.id','=', 'tongrens.tongren_cp_tag_id');
        return $query;
    }

    public function join_no_book_thread_tables(){
        $query = DB::table('threads')
        ->join('channels', 'threads.channel_id','=','channels.id')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->join('labels', 'threads.label_id', '=', 'labels.id')
        ->leftjoin('posts','threads.last_post_id','=', 'posts.id');
        return $query;
    }

    public function join_no_tongren_thread_tables(){
        $query = DB::table('threads')
        ->join('channels', 'threads.channel_id','=','channels.id')
        ->join('users', 'threads.user_id', '=', 'users.id')
        ->join('labels', 'threads.label_id', '=', 'labels.id')
        ->join('books', 'threads.book_id', '=', 'books.id')
        ->leftjoin('posts','threads.last_post_id','=', 'posts.id');
        return $query;
    }

    public function return_thread_fields($query){
        $query->select('books.thread_id','books.book_status', 'books.book_length', 'books.lastaddedchapter_at', 'books.total_char', 'books.last_chapter_id', 'books.sexual_orientation',
        'threads.id','threads.user_id','threads.book_id', 'threads.title', 'threads.brief', 'threads.locked', 'threads.public', 'threads.bianyuan', 'threads.anonymous', 'threads.majia', 'threads.noreply', 'threads.viewed', 'threads.responded', 'threads.lastresponded_at', 'threads.channel_id', 'threads.label_id', 'threads.deleted_at', 'threads.created_at', 'threads.edited_at', 'threads.homework_id', 'threads.post_id', 'threads.last_post_id', 'threads.show_homework_profile', 'threads.downloaded',
        'users.name', 'labels.labelname', 'channels.channelname', 'posts.body as last_post_body',
         'tongrens.tongren_yuanzhu',  'tongrens.tongren_cp', 'tongrens.tongren_yuanzhu_tag_id', 'tongrens.tongren_cp_tag_id', 'chapters.title as last_chapter_title', 'chapters.responded as last_chapter_responded', 'chapters.post_id as last_chapter_post_id', 'tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname', 'tongren_cp_tags.tagname as tongren_cp_tagname');
        return $query;
    }

    public function return_no_book_thread_fields($query){
        $query->select('threads.id', 'threads.id  as thread_id' ,'threads.user_id','threads.book_id', 'threads.title', 'threads.brief', 'threads.locked', 'threads.public', 'threads.bianyuan', 'threads.anonymous', 'threads.majia', 'threads.noreply', 'threads.viewed', 'threads.responded', 'threads.lastresponded_at',  'threads.channel_id', 'threads.label_id', 'threads.deleted_at', 'threads.created_at',  'threads.edited_at', 'threads.homework_id', 'threads.post_id', 'threads.last_post_id', 'threads.show_homework_profile', 'threads.downloaded', 'users.name', 'labels.labelname', 'channels.channelname', 'posts.body as last_post_body');
        return $query;
    }

    public function return_no_tongren_thread_fields($query){
        $query->select('books.thread_id','books.book_status', 'books.book_length', 'books.lastaddedchapter_at', 'books.total_char', 'books.last_chapter_id', 'books.sexual_orientation',
        'threads.id','threads.user_id','threads.book_id', 'threads.title', 'threads.brief', 'threads.locked', 'threads.public', 'threads.bianyuan', 'threads.anonymous', 'threads.majia', 'threads.noreply', 'threads.viewed', 'threads.responded', 'threads.lastresponded_at', 'threads.channel_id', 'threads.label_id', 'threads.deleted_at', 'threads.created_at', 'threads.edited_at', 'threads.homework_id', 'threads.post_id', 'threads.last_post_id', 'threads.show_homework_profile', 'threads.downloaded',
        'users.name', 'labels.labelname', 'channels.channelname', 'posts.body as last_post_body');
        return $query;
    }
}
