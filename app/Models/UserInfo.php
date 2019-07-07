<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserInfo extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'user_id';
    const UPDATED_AT = null;
    protected $dates = ['created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reward($exp=0, $jifen=0, $shengfan=0, $xianyu=0, $sangdian=0)
    {
        $this->exp+=$exp;
        $this->jifen+=$jifen;
        $this->shengfan+=$shengfan;
        $this->xianyu+=$xianyu;
        $this->sangdian+=$sangdian;
        $this->save();
    }

    public function activate_user(){
        $user = $this->user;
        $user->activated = true;
        $this->activation_token = null;
        $user->save();
        $this->save();
    }
    public function active_now($ip=null){
        $this->active_at = Carbon::now();
        $this->save();
    }


}
