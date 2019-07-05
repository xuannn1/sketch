<?php
namespace App\Sosadfun\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use App\Models\Label;
use App\Models\Tag;
use Carbon\Carbon;
use App\Helpers\Helper;

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
        'tongren_yuanzhu_tags.tagname as tongren_yuanzhu_tagname','tongren_cp_tags.tagname as tongren_cp_tagname', 'threads.top', 'threads.recommended','threads.jinghua');
        return $query;
    }

    public function return_recommend_book_fields($query){
        $query->select('recommend_books.id','recommend_books.thread_id','recommend_books.valid','recommend_books.clicks','recommend_books.recommendation','recommend_books.past','recommend_books.long','threads.title','threads.bianyuan');
        return $query;
    }

    public function bookOrderBy($query, $orderby){//1:按最新章节, 2:按最新回贴时间, 3:积分排序, 4.字数均衡积分
        switch ($orderby) {
            case 2://最新回复
            $query->orderBy('threads.lastresponded_at', 'desc');
            return $query;
            break;
            case 3://总积分
            $query->orderBy('threads.jifen', 'desc');
            return $query;
            break;
            case 4://字数平衡积分
            $query->orderBy('books.weighted_jifen', 'desc');
            return $query;
            break;
            default://默认书籍按照最新章节排序
            $query->orderBy('books.lastaddedchapter_at', 'desc');
            return $query;
        }
    }
}
