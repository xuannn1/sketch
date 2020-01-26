<?php
namespace App\Sosadfun\Traits;

use Cache;
use DB;
use ConstantObjects;
use App\Models\RegistrationApplication;
use Auth;

trait RegistrationApplicationObjectTraits{

    public function refreshCheckApplicationViaEmail($email)
    {
        Cache::forget('checkApplicationViaEmail.'.$email);
    }

    public function findApplicationViaEmail($email)
    {
        return Cache::remember('findApplicationViaEmail.'.$email, 30, function() use($email) {
            $application = RegistrationApplication::where('email',$email)->first();
            return $application;
        });
    }

    public function refreshFindApplicationViaEmail($email)
    {
        Cache::forget('findApplicationViaEmail.'.$email);
    }

    public function checkApplicationViaEmail($email)
    {
        return Cache::remember('checkApplicationViaEmail.'.$email, 30, function() use($email) {
            $existing_user = DB::table('users')->where('email',$email)->first();
            if($existing_user){
                return ['warning'=>'该邮箱已注册'];
            }

            $blocked_email = ConstantObjects::black_list_emails()->where('email',$email)->first();
            if($blocked_email){
                return ['danger'=>'本邮箱'.$email.'存在违规记录，已被拉黑。'];
            }
            return ['success'=>'本邮箱可用'];
        });
    }

}
