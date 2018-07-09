<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }
    public function collection_list()
    {
        return $this->belongsTo(CollectionList::class, 'collection_list_id')->withDefault();
    }
}
