<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\ConstantObjects;
use App\Sosadfun\Traits\ColumnTrait;

use DB;

class Thread extends Model
{
    use SoftDeletes, ColumnTrait;
    use Traits\VoteTrait;
    use Traits\RewardTrait;
    use Traits\ValidateTagTraits;

    protected $guarded = [];
    protected $hidden = [
        'creation_ip',
    ];
    protected $dates = ['deleted_at'];

    const UPDATED_AT = null;

    //以下是relationship关系
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function channel()
    {
        return collect(config('channel'))->keyby('id')->get($this->channel_id);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name', 'title_id');
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

    public function last_component()
    {
        return $this->belongsTo(Post::class, 'last_component_id');
    }

    public function last_post()
    {
        return $this->belongsTo(Post::class, 'last_post_id');
    }

    public function collectors()
    {
        return $this->belongsToMany(User::class, 'collections', 'thread_id', 'user_id')->select(['id','name']);
    }


    //以下是scopes

    public function scopeInChannel($query, $withChannels)
    {
        if($withChannels){
            $channels=(array)json_decode($withChannels);
            if(!empty($channels)){
                return $query->whereIn('channel_id', $channels);
            }
        }
        return $query;
    }

    public function scopeWithType($query, $withType)
    {
        if($withType){
            return $query->whereIn('channel_id', ConstantObjects::publicChannelTypes($withType));
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

    public function scopeWithTag($query, $withTags)
    {
        if ($withTags){
            $tags=(array)json_decode($withTags);
            return $query->whereHas('tags', function ($query) use ($tags){
                $query->whereIn('id', $tags);
            });
        }else{
            return $query;
        }
    }

    public function scopeExcludeTag($query, $excludeTags)
    {
        if ($excludeTags){
            $tags=(array)json_decode($excludeTags);
            return $query->whereDoesntHave('tags', function ($query) use ($tags){
                $query->whereIn('id', $tags);
            });
        }else{
            return $query;
        }
    }

    public function scopeIsPublic($query)//在thread index的时候，只看公共channel内的公开thread
    {
        return $query->where('is_public', true)->whereIn('channel_id', ConstantObjects::public_channels());
    }

    public function scopeThreadInfo($query)
    {
        return $query->select(array_diff( $this->thread_columns, ['body']));
    }

    public function scopeThreadBrief($query)
    {
        return $query->select($this->threadbrief_columns);
    }

    public function scopeOrdered($query, $ordered)
    {
        switch ($ordered) {
            case 'latest_add_component'://最新更新
            return $query->orderBy('add_component_at', 'desc');
            break;
            case 'jifen'://总积分
            return $query->orderBy('jifen', 'desc');
            break;
            case 'weighted_jifen'://字数平衡积分
            return $query->orderBy('weighted_jifen', 'desc');
            break;
            case 'latest_created'://创建时间
            return $query->orderBy('created_at', 'desc');
            break;
            case 'id'://创建顺序
            return $query->orderBy('id', 'asc');
            break;
            case 'collection_count'://收藏数
            return $query->orderBy('collection_count', 'desc');
            break;
            case 'random'://随机排序
            return $query->inRandomOrder();
            break;
            case 'total_char'://总字数
            return $query->orderBy('total_char', 'desc');
            break;
            default://默认按最后回复排序
            return $query->orderBy('responded_at', 'desc');
        }
    }
    // 以下是其他function


    public function remove_custom_tags()//去掉所有用户自己提交的tag,返回成功去掉的
    {
        $detach_tags = [];
        foreach($this->tags as $tag){
            if(!array_key_exists($tag->tag_type, config('tag.limits.user_not_manageable'))){
                array_push($detach_tags,$tag->id);
            }
        }
        if(!empty($detach_tags)){
            $this->tags()->detach($detach_tags);
            return count($detach_tags);
        }else{
            return 0;
        }
    }

    public function count_char()//计算本thread内所有chapter的characters总和
    {
        return  DB::table('posts')
        ->where('deleted_at', '=', null)
        ->whereNotIn('type',['post','comment'])
        ->where('thread_id', '=', $this->id)
        ->sum('char_count');
    }

    public function most_upvoted()//这个thread里面，普通的post中，最多赞的评论
    {
        return Post::postInfo()
        ->where('thread_id', $this->id)
        ->where('type', '=', 'post')
        ->orderBy('upvote_count', 'desc')
        ->first();
    }

    public function top_review()//对这个thread的review里最热门的一个
    {
        return Post::join('reviews', 'posts.id', '=', 'reviews.post_id')
        ->where('reviews.thread_id', $this->id)
        ->where('reviews.recommend', true)
        ->where('reviews.author_disapprove', false)
        ->orderby('reviews.redirects', 'desc')
        ->select('posts.*')
        ->first();
    }

}
