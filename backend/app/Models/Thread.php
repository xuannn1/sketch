<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\ConstantObjects;

class Thread extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = [
        'creation_ip',
    ];
    protected $dates = ['deleted_at'];

    protected $threadinfo_columns = array('id', 'user_id', 'thread_group', 'channel_id', 'label_id',  'title', 'brief', 'last_post_id', 'last_post_preview', 'is_anonymous', 'majia', 'created_at', 'last_edited_at', 'xianyus', 'shengfans', 'views', 'replies', 'collections', 'downloads', 'jifen', 'weighted_jifen', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'is_top', 'is_popular', 'is_highlighted', 'last_responded_at', 'deleted_at'); // 使诸如文案这样的文本信息，在一些时候不被检索，减少服务器负担

    protected $bookinfo_columns = array('id', 'user_id', 'thread_group', 'channel_id', 'label_id',  'title', 'brief', 'last_post_id', 'last_post_preview', 'is_anonymous', 'majia', 'created_at', 'last_edited_at', 'xianyus', 'shengfans', 'views', 'replies', 'collections', 'downloads', 'jifen', 'weighted_jifen', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'is_top', 'is_popular', 'is_highlighted', 'last_responded_at', 'book_status',  'book_length', 'sexual_orientation', 'last_added_chapter_at', 'last_chapter_id','deleted_at','total_char'); // 使诸如文案这样的文本信息，在一些时候不被检索，减少服务器负担

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function simpleChannel()
    {
        return ConstantObjects::allChannels()->keyBy('id')->get($this->channel_id)->only(['id','channel_name']);
    }

    public function simpleLabel()
    {
        return ConstantObjects::allLabels()->keyBy('id')->get($this->label_id)->only('id','label_name');
    }

    public function scopeInLabel($query, $withLabel)
    {
        if($withLabel){
            $labels=explode('_',$withLabel);
            if(!empty($labels)){
                return $query->whereIn('label_id', $labels);
            }
        }
        return $query;
    }

    public function scopeInChannel($query, $withChannel)
    {
        if($withChannel){
            $channels=explode('_',$withChannel);
            if(!empty($channels)){
                return $query->whereIn('channel_id', $channels);
            }
        }
        return $query;
    }

    public function scopeIsBook($query)
    {
        return $query->where('channel_id', '<=', 2);
    }
    public function scopeIsNotBook($query)
    {
        return $query->where('channel_id', '>', 2);
    }

    public function scopeWithTag($query, $withTag)
    {
        if ($withTag){
            $tags=explode('_',$withTag);
            return $query->whereHas('tags', function ($query) use ($tags){
                $query->whereIn('id', $tags);
            });
        }else{
            return $query;
        }
    }

    public function scopeWithBookLength($query, $withBookLength)
    {
        if($withBookLength){
            $booklength=explode('_',$withBookLength);
            if((!empty($booklength))&&(count($booklength)<count(config('constants.book_info.book_length_info')))){
                return $query->whereIn('book_length', $booklength);
            }
        }
        return $query;
    }
    public function scopeWithBookStatus($query, $withBookStatus)
    {
        if($withBookStatus){
            $bookstatus=explode('_',$withBookStatus);
            if((!empty($bookstatus))&&(count($bookstatus)<count(config('constants.book_info.book_status_info')))){
                return $query->whereIn('book_status', $bookstatus);
            }
        }
        return $query;
    }
    public function scopeWithSexualOrientation($query, $withSexualOrientation)
    {
        if($withSexualOrientation){
            $sexualorientation=explode('_',$withSexualOrientation);
            if((!empty($sexualorientation))&&(count($sexualorientation)<count(config('constants.book_info.book_status_info')))){
                return $query->whereIn('sexual_orientation', $sexualorientation);
            }
        }
        return $query;
    }
    public function scopeIsBianyuan($query, $isBianyuan)
    {
        if ($isBianyuan==='true'){
            return $query->where('is_bianyuan', true);
        }
        if ($isBianyuan==='false'){
            return $query->where('is_bianyuan', false);
        }
        return $query;
    }
    public function scopeIsPublic($query)
    {
        return $query->where('is_public', true);
    }
    public function scopeCanSee($query, $group)
    {
        return $query->where('thread_group', '<' , $group);
    }
    public function scopeThreadInfo($query)
    {
        return $query->select($this->threadinfo_columns);
    }
    public function scopeBookInfo($query)
    {
        return $query->select($this->bookinfo_columns);
    }
}
