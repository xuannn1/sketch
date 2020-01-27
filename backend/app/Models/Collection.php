<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id','name','title_id');
    }

    public function thread()//收藏的对象
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }

    public function briefThread()//收藏的对象
    {
        return $this->belongsTo(Thread::class, 'thread_id')->brief();
    }

    public function group()//从属的收藏页
    {
        return $this->belongsTo(CollectionGroup::class, 'group_id');
    }

    public function scopeThreadOrdered($query, $ordered='')
    {
        // see constant.php 'collection_group_order_by'
        switch ($ordered) {
            case 2://最新更新章节
            return $query->orderBy('threads.add_component_at', 'desc');
            break;

            case 1://最新回复
            return $query->orderBy('threads.responded_at', 'desc');
            break;

            case 3://最新创建
            return $query->orderBy('threads.created_at', 'desc');
            break;

            default:
            return $query->orderBy('collections.id', 'desc'); //最新收藏
            break;
        }
    }
}
