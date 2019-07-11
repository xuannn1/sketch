<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ResetPasswordNotification;
use DB;
use Carbon\Carbon;
use Cache;
use Helper;

class User extends Authenticatable
{
    use Notifiable;

    protected $dates = ['deleted_at', 'qiandao_at'];
    public $timestamps = false;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'title_id'
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'email', 'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
    }
    /**
    * Send the password reset notification.
    *
    * @param  string  $token
    * @return void
    */
    //overriding existing sendpassword reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function titles()
    {
        return $this->belongsToMany(Title::class, 'title_user', 'user_id', 'title_id')->withPivot('is_public');
    }

    public function linkedaccounts()
    {
        return $this->belongsToMany(User::class, 'linkaccounts', 'master_account', 'branch_account');
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    public function collectedItems()
    {
        return $this->belongsToMany('App\Models\Thread', 'collection_count', 'user_id', 'thread_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'user_id');
    }

    public function homeworks()
    {
        return $this->belongsToMany(Homework::class, 'homework_registrations', 'homework_id', 'user_id');
    }



    public function follow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }
    public function unfollow($user_ids)
    {
        if (!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }


    public function isAdmin()
    {
        return $this->role==='admin';
    }

    public function isEditor()
    {
        return $this->role==='editor';
    }

    public function seeHomework()
    {
        return $this->role==='admin'||$this->role==='editor'||$this->role==='senior';
    }

    public function canSeeChannel($id)
    {
        $channel = collect(config('channel'))->keyby('id')->get($id);
        return $channel->is_public||$this->role==='admin'||($channel->type==='homework'&&$this->role==='editor')||($channel->type==='homework'&&$this->role==='senior');
    }

    public function checklevelup()
    {
        $level_ups = config('level.level_up');
        $info = $this->info;
        foreach($level_ups as $level=>$requirement){
            if (($this->level < $level)
            &&(!(array_key_exists('jifen',$requirement))||($requirement['jifen']<=$info->jifen))
            &&(!(array_key_exists('xianyu',$requirement))||($requirement['xianyu']<=$info->xianyu))
            &&(!(array_key_exists('sangdian',$requirement))||($requirement['sangdian']<=$info->sangdian))){
                $this->level = $level;
                $this->save();
                return true;
            }
        }
        return false;
    }

    public function reward($kind, $base = 0){
        return $this->info->reward($kind, $base);
    }

    public function isOnline()
    {
        return Cache::has('usr-on-' . $this->id);
    }

    public function wearTitle($title_id)
    {
        $this->update([
            'title_id' => $title_id,
        ]);
    }

    public function active_now($ip)
    {
        $this->info->active_now($ip);
    }



}
