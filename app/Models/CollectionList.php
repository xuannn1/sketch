<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionList extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function collected_items()
    {
        switch ($this->type):
            case '1'://书籍收藏
                return $this->belongsToMany(Thread::class, 'collections', 'collection_list_id', 'item_id')->where('book_id', '>', 0)->withPivot('updated', 'keep_updated', 'collection_list_id');
            break;
            case '2'://讨论帖收藏
                return $this->belongsToMany(Thread::class, 'collections', 'collection_list_id', 'item_id')->where('book_id', '=', 0)->withPivot('updated', 'keep_updated', 'collection_list_id');
            break;
            case '3'://帖子收藏
                return $this->belongsToMany(Post::class, 'collections', 'collection_list_id', 'item_id')->where('book_id', '=', 0)->withPivot('updated', 'keep_updated', 'collection_list_id');
            break;
            case 0://空集
            case '4'://收藏单收藏
                return $this->belongsToMany(CollectionList::class, 'collections', 'collection_list_id', 'item_id')->withPivot('updated', 'keep_updated', 'collection_list_id');
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
        endswitch;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id')->select(['id','name'])->withDefault();
    }

    public function last_collected_item()
    {
        switch ($this->type):
            case "1"://书籍收藏
                return $this->hasOne(Thread::class,'last_item_id')->withDefault();
            break;
            case "2"://讨论帖收藏
                return $this->hasOne(Thread::class,'last_item_id')->withDefault();
            break;
            case "3"://帖子收藏
                return $this->hasOne(Post::class,'last_item_id')->withDefault();
            break;
            case "4"://收藏单收藏
                return $this->hasOne(CollectionList::class,'last_item_id')->withDefault();
            break;
            default:
            echo "应该奖励什么呢？一个bug呀……";
        endswitch;
    }
}
