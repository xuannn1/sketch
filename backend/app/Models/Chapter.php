<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $mainpost_columns = array ('id','created_at','last_edited_at','up_votes','down_votes','fold_votes','funny_votes','xianyus','shengfans','replies','is_popular','is_bianyuan','last_responded_at');

    public function mainpost()
    {
        return $this->belongsTo(Post::class, 'post_id')->select($this->mainpost_columns);
    }
    public function volumn()
    {
        return $this->belongsTo(Volumn::class, 'volumn_id');
    }
}
