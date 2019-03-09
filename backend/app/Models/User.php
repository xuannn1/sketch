<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Sosadfun\Traits\ColumnTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Helpers\ConstantObjects;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, ColumnTrait;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const UPDATED_AT = null;

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

    public function roles()
    {
        return ConstantObjects::role_users()->where('user_id', $this->id);
    }

    public function mainTitle()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function titles()
    {
        return $this->belongsToMany('App\Models\Title', 'title_user', 'user_id', 'title_id')->withPivot('is_public');
    }

    public function publicTitles()
    {
        return $this->belongsToMany('App\Models\Title', 'title_user', 'user_id', 'title_id')->wherePivot('is_public', true)->withPivot('is_public');
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }

    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    public function collectedItems()
    {
        return $this->belongsToMany('App\Models\Thread', 'collection_count', 'user_id', 'thread_id');
    }


    /**
    * 查看对应用户的roles里面是否含有某种对应的global permission
    * 举例：$user->hasAccess(['can_see_homework', 'can_see_ip_addresses']) returns true
    */
    public function hasAccess(array $permissions) : bool
    {
        foreach ($this->roles() as $role) {
            $role_permissions = config('role.roles')[$role->role]?? [];
            foreach($permissions as $permission){
                if ($role_permissions[$permission]?? false){
                    return true;
                }
            }
        }
        return false;
    }
    /**
    * 查看对应用户的roles里面是否含有特别针对某个channel或者homework的局部permission
    * 举例：$user->hasLocalAccess('can_see_ip_addresses_in_channel', 1) returns false
    */
    public function hasLocalAccess($permission, $option) : bool
    {
        foreach ($this->roles() as $role) {
            $role_permissions = config('role.roles')[$role->role]?? [];
            if ($role_permissions[$permission]?? false){
                if (json_decode($role->options)->{$option}?? false){
                    return true;
                }
            }
        }
        return false;
    }

    /**
    * Checks if the user can visit specific channel
    */
    public function canSeeChannel($channel) : bool
    {
        return $this->hasAccess(['can_see_anything'])||$this->hasLocalAccess('can_see_channel', $channel);
    }

    public function canRecommend() : bool
    {
        return $this->hasAccess(['can_recommend','can_manage_anything']);
    }

    public function canManageChannel($channel) : bool
    {
        return $this->hasAccess(['can_manage_anything'])||$this->hasLocalAccess('can_manage_channel', $channel);
    }

    /**
    * Checks if the user belongs to role.
    */
    public function inRole(string $roleSlug)
    {
        return $this->roles()->where('role', $roleSlug)->count() == 1;
    }

    public function isAdmin()
    {
        return $this->inRole('admin');
    }
}
