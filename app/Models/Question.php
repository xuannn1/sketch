<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GrahamCampbell\Markdown\Facades\Markdown;

class Question extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function questioner()
    {
        return $this->belongsTo(User::class, 'questioner_id')->select(['id','name'])->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'answer_id')->withDefault();
    }


}
