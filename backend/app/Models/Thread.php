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

    protected $threadinfo_columns = array('id', 'user_id', 'channel_id',  'title', 'brief', 'last_post_id', 'is_anonymous', 'majia', 'created_at', 'last_edited_at', 'xianyus', 'shengfans', 'views', 'replies', 'collections', 'downloads', 'jifen', 'weighted_jifen', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'last_responded_at', 'last_added_component_at', 'last_component_id', 'deleted_at', 'total_char'); // 使诸如文案这样的文本信息，在一些时候不被检索，减少服务器负担

    protected $threadbrief_columns = array('id', 'user_id', 'channel_id',  'title',  'is_anonymous', 'majia', 'is_public', 'is_bianyuan', 'last_responded_at', 'last_added_component_at', 'deleted_at'); // 极简版的信息

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

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class)->where('is_public', true);
    }

    public function simpleChannel()
    {
        return
        ConstantObjects::allChannels()->keyBy('id')->get($this->channel_id);
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
            case 'last_added_component_at'://最新更新
            return $query->orderBy('last_added_component_at', 'desc');
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

    public function tags_validate($tags,$is_bianyuan)
    {
        $sum_limit_count =config('tag.limits.sum_limit_count');
        $valid_tags = [];
        foreach($tags as $key => $value){
            $tag = ConstantObjects::allTags()->keyBy('id')->get($value);
            // 3. 非大类的tag(channel_id=0)，检查有没有tag和其他信息的冲突
            if (!array_key_exists($tag->tag_type,config('tag.types'))){
                //3.1非边限选边限tag，
                if((!$is_bianyuan) && $tag->is_bianyuan){
                    abort(422);
                }
                //3.2 如不属于某channel却选择了专属于某channel的tag,如非同人选择了同人
                if( $tag->channel_id !== $this->channel_id){
                    abort(422);
                }

                //1）检查是否满足某些类tag只能选一个的限制情况，
                //篇幅,性向,进度,同人原著,同人CP,结局
                if (array_key_exists($tag->tag_type,config('tag.limits.only_one'))){
                    if(array_key_exists($tag->tag_type,$only_one_tags)){
                        abort(422);
                    }else{
                        $only_one_tags[$tag->tag_type] = $tag->id;
                    }
                }

                // 2）检查数目限制的那些是否满足要求， sum_limit < sum_limit_count
                //故事气氛,整体时代,强弱关系,,,,<3
                if (array_key_exists($tag->tag_type,config('tag.limits.sum_limit'))){
                    if(!empty($limit_count_tags)&&(count($limit_count_tags)>$sum_limit_count)){
                        abort(422);
                    }else{
                        $limit_count_tags[$tag->id]=$tag->id;
                    }
                }


                //3,保存同人信息
                //用于同人CP寻找同人原著，同人原著寻找同人作品其他分类
                if($tag->type === '同人CP')
                {
                    $tongren_tags[$tag->id] = $tag->parent_id;
                }

                //4）检查是否出现了用户提交不应由普通用户添加的tag (这类tag直接剥离)
                if(!$tag->user_not_manageable()){
                    $valid_tags[$tag->id]=$tag->id;
                }

            }else{
                $valid_tags[$tag->id]=$tag->id;  //channel=0的大类直接加入
            }
        }//循环结束

        //用于同人CP寻找同人原著，同人原著寻找同人作品其他分类
        //同人CP找不到对应的同人原著 同人原著找不到对应的其他同人作品分类 报错
        if(!empty($tongren_tags)){
            foreach($tongren_tags as $child => $parent){
                if($parent>0 && !array_key_exists($parent,$valid_tags)){
                    abort(422);
                }
            }
        }

        //5,与原threads 管理员加的标签合并
        $manageable_tags = config('tag.limits.user_not_manageable');
        foreach($this->tags()->get() as $tag){
            if(array_key_exists($tag->tag_type,$manageable_tags)){
                $valid_tags[$tag->id] = $tag->id;
            }
        }
        
        return $valid_tags;
    }

}
