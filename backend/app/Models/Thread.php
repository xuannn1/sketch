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

    protected $threadinfo_columns = array('id', 'user_id', 'channel_id',  'title', 'brief', 'last_post_id', 'is_anonymous', 'majia', 'created_at', 'last_edited_at', 'xianyus', 'shengfans', 'views', 'replies', 'collections', 'downloads', 'jifen', 'weighted_jifen', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'last_responded_at', 'last_added_chapter_at', 'last_chapter_id', 'deleted_at', 'total_char'); // 使诸如文案这样的文本信息，在一些时候不被检索，减少服务器负担

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

    public function votes()
    {
        return $this->morphMany('App\Models\Vote', 'votable');
    }

    public function simpleChannel()
    {
        return ConstantObjects::allChannels()->keyBy('id')->get($this->channel_id)->only(['id','channel_name']);
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
        return $query->whereIn('channel_id', ConstantObjects::book_channels());
    }
    public function scopeIsNotBook($query)
    {
        return $query->whereIn('channel_id', ConstantObjects::none_book_channels());
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

    public function scopeWithoutTag($query, $withoutTag)
    {
        if ($withoutTag){
            $tags=explode('_',$withoutTag);
            return $query->whereDoesntHave('tags', function ($query) use ($tags){
                $query->whereIn('id', $tags);
            });
        }else{
            return $query;
        }
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

    public function scopeThreadInfo($query)
    {
        return $query->select($this->threadinfo_columns);
    }
}
