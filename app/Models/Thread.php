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
    use Traits\TypeValueChangeTrait;
    use Traits\ThreadTongrenTraits;
    use Traits\RecordViewTrait;

    protected $guarded = [];
    protected $hidden = [
        'creation_ip',
    ];
    protected $dates = ['deleted_at','created_at','responded_at', 'edited_at', 'add_component_at'];

    protected $count_types = array('salt','fish','ham');

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

    public function scopeInfo($query)
    {
        return $query->select('id', 'user_id', 'channel_id', 'title', 'brief');
    }

    public function scopeBrief($query)
    {
        return $query->select('id', 'user_id', 'channel_id', 'title', 'brief', 'is_locked', 'is_public', 'is_bianyuan', 'is_anonymous', 'majia', 'view_count', 'reply_count', 'responded_at', 'created_at', 'collection_count', 'no_reply', 'last_post_id', 'last_component_id', 'weighted_jifen', 'total_char');
    }

    public function scopeInChannel($query, $withChannels)
    {
        if($withChannels){
            $channels = explode('-',$withChannels);
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

    public function scopeWithoutType($query, $withoutType="")
    {
        if($withoutType){
            return $query->whereNotIn('channel_id', ConstantObjects::publicChannelTypes($withoutType));
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

    public function scopeWithTag($query, $withTags="")// (A||B)&&(C||D):A_B-C_D
    {
        if ($withTags){
            $andtags = explode('-',$withTags);
            foreach($andtags as $andtag){
                $parallel_tags = explode('_', $andtag);
                if($parallel_tags){
                    $query = $query->whereHas('tags', function ($query) use ($parallel_tags){
                        $query->whereIn('tags.id', $parallel_tags);
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

    public function scopeExcludeTag($query, $excludeTag="")// no A, no B, no C: A-B-C
    {
        if ($excludeTag){
            $tags = explode('-',$excludeTag);
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

    public function scopeInPublicChannel($query, $inPublicChannel='')//只看公共channel内的
    {
        if($inPublicChannel==='include_none_public_channel'){
            return $query;
        }
        return $query->whereIn('channel_id', ConstantObjects::public_channels());
    }

    public function scopeIsPublic($query, $isPublic='')//只看作者决定公开的
    {
        if($isPublic==='include_private'){
            return $query;
        }
        if($isPublic==='private_only'){
            return $query->where('is_public', false);
        }
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

    public function random_editor_recommendation()
    {
        return Review::with('post.author')
        ->ThreadOnly($this->id)
        ->withEditorRecommend('editor_recommend_only')
        ->inRandomOrder()
        ->first();
    }

    public function register_homework()
    {
        // TODO 检查这名同学参加了作业吗？是的话算他提交了作业
    }

    public function max_chapter_order()
    {
        return DB::table('posts')
        ->join('chapters','chapters.post_id','=','posts.id')
        ->where('posts.thread_id','=',$this->id)
        ->select('chapters.order_by')
        ->max('chapters.order_by');
    }

    public function recalculate_characters()
    {
        $sum_char = Post::where('thread_id',$this->id)
        ->withComponent('component_only')
        ->sum('char_count');
        $this->update(['total_char'=>$sum_char]);
        return $sum_char;
    }

    public function reorder_chapters()
    {
        $posts = Post::with('chapter')
        ->join('chapters', 'posts.id', '=', 'chapters.post_id')
        ->where('posts.thread_id',$this->id)
        ->where('posts.type','chapter')
        ->orderBy('chapters.order_by', 'asc')
        ->select('posts.id')
        ->get();
        $previous = null;
        $first = null;
        foreach($posts as $post){
            if(!$first){
                $first = $post;
            }
            if($previous){
                if($post->chapter->previous_id<>$previous->id){
                    $post->chapter->update(['previous_id' => $previous->id]);
                }
                if($previous->chapter->next_id<>$post->id){
                    $previous->chapter->update(['next_id' => $post->id]);
                }
            }
            $previous = $post;
        }
        if($first){
            if($this->first_component_id<>$first->id){
                $this->first_component_id=$first->id;
            }
        }
        if($previous){
            if($this->last_component_id<>$previous->id){
                $this->last_component_id=$previous->id;
            }
        }
        $this->save();
        return;
    }

    public function check_bianyuan()// 章节/书评 总数超过20%的自动升级成边限
    {
        $component_type = $this->channel()->type==='book'? 'chapter':'';
        $component_type = $this->channel()->type==='list'? 'review':'';

        if($component_type){
            $total_components = $this->posts()->withType($component_type)->count();
            $bianyuan_components = $this->posts()->withType($component_type)->withBianyuan('bianyuan_only')->count();

            if(($bianyuan_components*5>$total_components)&&$this->is_bianyuan===0){
                $this->update(['is_bianyuan'=>1]);
                return false;
            }
        }

        return true;
    }
}
