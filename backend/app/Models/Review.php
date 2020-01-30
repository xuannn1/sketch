<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use Traits\DelayCountTrait;

    protected $guarded = [];
    public $timestamps = false;
    protected $primaryKey = 'post_id';

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function reviewee()//被评论的文章
    {
        return $this->belongsTo(Thread::class, 'thread_id')->select('id','user_id','channel_id','title','brief','is_bianyuan','is_anonymous','is_public','no_reply');
    }

    public function scopeThreadOnly($query, $threadOnly)
    {
        if($threadOnly){
            return $query->where('thread_id', $threadOnly);
        }
        return $query;
    }
    public function scopeWithEditorRecommend($query, $withEditorRecommend='')
    {
        if($withEditorRecommend==='editor_recommend_only'){
            return $query->where('editor_recommend',1);
        }
        if($withEditorRecommend==='none_editor_recommend_only'){
            return $query->where('editor_recommend',0);
        }
        return $query;
    }

}
