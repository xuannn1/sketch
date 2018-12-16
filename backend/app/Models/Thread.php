<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\FilterThreadScope;
use App\Helpers\Helper;

class Thread extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = [
        'creation_ip',
    ];
    protected $dates = ['deleted_at'];

    const UPDATED_AT = null;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new FilterThreadScope);
    }

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
        return Helper::allChannels()->keyBy('id')->get($this->channel_id)->only(['id','channel_name']);
    }

    public function simpleLabel()
    {
        return Helper::allLabels()->keyBy('id')->get($this->label_id)->only('id','label_name');
    }

    public function simpleTag()
    {
        return Helper::allTags()->keyBy('id')->get($this->tag_id)->only('id','tag_name','tag_explanation');
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

    public function scopeIsBook($query, $isBook)
    {
        if ($isBook==='true'){
            return $query->where('channel_id', '<=', 2);
        }
        if ($isBook==='false'){
            return $query->where('channel_id', '>', 2);
        }
        return $query;
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
}
