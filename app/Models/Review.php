<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
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

    // public function check_recommendation()
    // {
    //     if($this->editor_recommend&&$this->reviewee&&!$this->reviewee->recommended){
    //         $this->reviewee->update(['recommended'=>true]);
    //     }
    //     if(!$this->editor_recommend&&$this->reviewee&&$this->reviewee->recommended){
    //         $this->reviewee->update(['recommended'=>false]);
    //     }
    // }

}
