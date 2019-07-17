<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GrahamCampbell\Markdown\Facades\Markdown;

class Post extends Model
{
    use SoftDeletes;
    use Traits\PostFilterable;
    use Traits\RegularTraits;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id')->withDefault();
    }

    public function simpleThread()
    {
        return $this->belongsTo(Thread::class, 'thread_id')->select('id','user_id');
    }

    public function replies()
    {
        return $this->hasMany(Post::class, 'reply_to_id','id');
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class)->orderBy('created_at','desc');
    }
    public function allcomments()
    {
        return $this->hasMany(PostComment::class)->orderBy('created_at','desc');
    }
    public function shengfans()
    {
        return $this->hasMany(Shengfan::class)->orderBy('created_at','asc');
    }
    public function shengfan_voted(User $user)
    {
        return Shengfan::where('post_id', $this->id)->where('user_id', $user->id)->first();
    }
    public function reply_to_post()
    {
        return $this->belongsTo(Post::class, 'reply_to_post_id')->withDefault();
    }
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id')->withDefault();
    }


    // public function trim($str, $len)
    // {
    //    $body = preg_replace('/[[:punct:]\s\n\t\r]/','',$str);
    //    $body = trim($body);
    //    if (strlen($body)>$len){
    //       return $this->sub_str($body, $len, true);
    //    }else{
    //       return $body;
    //    }
    // }
    public function checklongcomment()//新建章节之后，检查是否属于长评范畴，如果属于，加入推荐队列
    {
        if (!$this->maintext){//必须不能是某章节正文
            $string = preg_replace('/[[:punct:]\s\n\t\r]/','',$this->body);
            $length = iconv_strlen($string, 'utf-8');
            if($length>=config('constants.longcomment_length')){
                $longcomment = LongComment::firstOrCreate(['post_id' => $this->id,]);
                $this->update(['long_comment'=>1,'long_comment_id'=>$longcomment->id]);
                $this->user->reward('longcomment');
            }else{
                $longcomment = LongComment::where('post_id',$this->id)->first();
                if($longcomment){
                    $longcomment->delete();
                }
                $this->update(['long_comment'=>0,'long_comment_id'=>0]);
            }
        }
        return;
    }
}
