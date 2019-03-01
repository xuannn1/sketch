<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Message extends Model
{
    protected $guarded = [];
    const UPDATED_AT = null;

    protected $dates = ['deleted_at'];

    public function poster()
    {
        return $this->belongsTo(User::class, 'poster_id')->select('id', 'name', 'title_id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id')->select('id', 'name', 'title_id');
    }
    public function body()
    {
        return $this->hasOne(MessageBody::class, 'id', 'message_body_id');
    }

    public function scopeWithReceiver($query, $receiver_id)
    {
        return $query->where('messages.receiver_id', $receiver_id);
    }

    public function scopeWithPoster($query, $poster_id)
    {
        return $query->where('messages.poster_id', $poster_id);
    }

    public function scopeWithDialogue($query, $user_id, $chatWith_id)
    {
        return $query->where(function($query) use($user_id, $chatWith_id) {
            $query->where('poster_id', $user_id)
            ->where('receiver_id', $chatWith_id);
        })
        ->orWhere(function($query) use($user_id, $chatWith_id) {
            $query->where('poster_id', $chatWith_id)
            ->where('receiver_id', $user_id);
        });
    }

    public function scopeWithRead($query, $readstatus)
    {
        if($readstatus === 'read_only') {
            return $query->where('seen', 1);
        }else if($readstatus === 'unread_only') {
            return $query->where('seen', 0);
        }
    }

    public function scopeWithOrdered($query, $ordered)
    {
        if($ordered === 'oldest') {
            return $query->orderBy('created_at', 'asc');
        }else {
            return $query->orderBy('created_at', 'desc');
        }
    }
}
