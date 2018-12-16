<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FilterThreadScope implements Scope
{
    /**
    * Apply the scope to a given Eloquent query builder.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $builder
    * @param  \Illuminate\Database\Eloquent\Model  $model
    * @return void
    */

    protected $columns = array('id', 'title', 'channel_id', 'label_id', 'brief', 'user_id', 'is_anonymous', 'majia', 'created_at', 'xianyus', 'shengfans', 'views', 'replies', 'collections', 'downloads', 'jifen', 'weighted_jifen', 'is_locked', 'is_public', 'is_bianyuan', 'no_reply', 'is_top', 'is_popular', 'is_highlighted', 'last_responded_at', 'book_status',  'book_length', 'sexual_orientation', 'last_added_chapter_at', 'last_chapter_id','deleted_at'); // 使诸如文案这样的文本信息，并不在平时被检索出来，减少服务器负担

    public function apply(Builder $builder, Model $model)
    {
        $builder->select($this->columns);
    }
}
