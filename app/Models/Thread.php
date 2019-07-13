<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use ConstantObjects;

use DB;

class Thread extends Model
{
    use SoftDeletes;
    use Traits\VoteTrait;
    use Traits\RewardTrait;
    use Traits\ValidateTagTraits;

    protected $guarded = [];
    protected $hidden = [
        'creation_ip',
    ];
    protected $dates = ['deleted_at','created_at','responded_at', 'edited_at', 'add_component_at'];

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
        return $this->belongsTo(User::class, 'user_id')->select('id','name', 'title_id','level');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class,'thread_id');
    }

    public function editor_recommends()
    {
        return $this->hasMany(Review::class,'thread_id')->where('editor_recommend','=',1);
    }

    public function user_recommends()
    {
        return $this->hasMany(Review::class,'thread_id')->where('editor_recommend','=',0);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function last_component()
    {
        return $this->belongsTo(Post::class, 'last_component_id')->select('id','type', 'user_id','title','brief','created_at');
    }

    public function last_chapter()
    {
        return $this->belongsTo(Post::class, 'last_component_id');
    }

    public function first_component()
    {
        return $this->belongsTo(Post::class, 'first_component_id')->select('id','type','user_id','title','brief','created_at');
    }

    public function rewards()
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }

    public function last_post()
    {
        return $this->belongsTo(Post::class, 'last_post_id')->select('id','type','user_id','title','brief','created_at');
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

    public function scopeWithType($query, $withType="")
    {
        if($withType){
            return $query->whereIn('channel_id', ConstantObjects::publicChannelTypes($withType));
        }
        return $query;
    }

    public function scopeWithBianyuan($query, $withBianyuan="")
    {
        if($withBianyuan==='include_bianyuan'){
            return $query;
        }
        if($withBianyuan==='bianyuan_only'){
            return $query->where('is_bianyuan', true);
        }
        return $query->where('is_bianyuan', false);
    }

    public function scopeWithTag($query, $withTags="")
    {
        if ($withTags){
            $tags = explode('-',$withTags);
            foreach($tags as $tag){
                if(is_numeric($tag)&&$tag>0){
                    $query = $query->whereHas('tags', function ($query) use ($tag){
                        $query->where('tags.id', '=', $tag);
                    });
                }
            }
        }
        return $query;
    }

    public function scopeWithUser($query, $id)
    {
        return $query->where('user_id','=',$id);
    }

    public function scopeWithAnonymous($query, $withAnonymous='')
    {
        if($withAnonymous==='anonymous_only'){
            return $query->where('is_anonymous','=',1);
        }
        if($withAnonymous==='none_anonymous_only'){
            return $query->where('is_anonymous','=',0);
        }
        return $query;

    }

    public function scopeExcludeTag($query, $excludeTag="")
    {
        if ($excludeTag){
            $tags = explode('-',$withTags);
            $exclude_tag = [];
            foreach($tags as $tag){
                if(is_numeric($tag)&&$tag>0){
                    array_push($exclude_tag, $tag);
                }
            }
            if($exclude_tag){
                return $query->whereDoesntHave('tags', function ($query) use ($exclude_tag){
                    $query->whereIn('tags.id', $exclude_tag);
                });
            }
        }
        return $query;
    }

    public function scopeInPublicChannel($query)//只看公共channel内的
    {
        return $query->whereIn('channel_id', ConstantObjects::public_channels());
    }

    public function scopeIsPublic($query)//只看作者决定公开的
    {
        return $query->where('is_public', true);
    }

    public function scopeOrdered($query, $ordered="")
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

    public function latest_rewards()
    {
        return Reward::with('author')
        ->withType('thread')
        ->withId($this->id)
        ->latest()
        ->take(10)
        ->get();
    }

    public function register_homework()
    {
        // TODO 检查这名同学参加了作业吗？是的话算他提交了作业
    }

    public function isCollectedBy($user_id)
    {
        return $this->collectors->contains($user_id);
    }
}
