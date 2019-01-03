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

    protected $threadbrief_columns = array('id', 'user_id', 'channel_id',  'title',  'is_anonymous', 'majia', 'is_public', 'is_bianyuan', 'last_responded_at', 'last_added_chapter_at', 'deleted_at'); // 极简版的信息

    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function channel()
    {
        return $this->belongsTo(Channel::class);
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
            $channels=json_decode($withChannel);
            if(!empty($channels)){
                return $query->whereIn('channel_id', $channels);
            }
        }
        return $query;
    }

    public function scopeWithBook($query, $withBook)
    {
        if($withBook==='book_only'){
            return $query->whereIn('channel_id', ConstantObjects::book_channels());
        }
        if($withBook==='none_book_only'){
            return $query->whereIn('channel_id', ConstantObjects::none_book_channels());
        }
        return $query;
    }

    public function scopeWithBianyuan($query, $withBianyuan)
    {
        if($withBianyuan==='bianyuan_only'){
            return $query->where('is_bianyuan', true);
        }
        if($withBianyuan==='none_bianyuan_only'){
            return $query->where('is_bianyuan', false);
        }
        return $query;
    }

    public function scopeWithTag($query, $withTag)
    {
        if ($withTag){
            $tags=json_decode($withTag);
            return $query->whereHas('tags', function ($query) use ($tags){
                $query->whereIn('id', $tags);
            });
        }else{
            return $query;
        }
    }

    public function scopeExcludeTag($query, $excludeTag)
    {
        if ($excludeTag){
            $tags=json_decode($excludeTag);
            return $query->whereDoesntHave('tags', function ($query) use ($tags){
                $query->whereIn('id', $tags);
            });
        }else{
            return $query;
        }
    }

    public function scopeIsPublic($query)//在index的时候，只看公共channel内的公开thread
    {
        return $query->where('is_public', true)->whereIn('channel_id', ConstantObjects::public_channels());
    }

    public function scopeThreadInfo($query)
    {
        return $query->select($this->threadinfo_columns);
    }

    public function scopeThreadBrief($query)
    {
        return $query->select($this->threadbrief_columns);
    }

    public function scopeOrdered($query, $ordered)
    {
        switch ($ordered) {
            case 'last_added_chapter_at'://最新回复
            return $query->orderBy('last_added_chapter_at', 'desc');
            break;
            case 'jifen'://总积分
            return $query->orderBy('jifen', 'desc');
            break;
            case 'weighted_jifen'://字数平衡积分
            return $query->orderBy('weighted_jifen', 'desc');
            break;
            case 'created_at'://创建时间
            return $query->orderBy('created_at', 'desc');
            break;
            case 'id'://创建顺序
            return $query->orderBy('id', 'desc');
            break;
            case 'collections'://收藏数
            return $query->orderBy('collections', 'desc');
            break;
            case 'total_char'://总字数
            return $query->orderBy('total_char', 'desc');
            break;
            default://默认按最后回复排序
            return $query->orderBy('last_responded_at', 'desc');
        }
    }
}
