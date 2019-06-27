<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon\Carbon;

trait SwitchableMailerTraits{
    public function select_server()
    {
        $count = Cache::get('switchable-mailer-count')?? 0;
        $tail = $count % env('SWITCHABLE_MAIL_SERVERS','1');
        Cache::put('switchable-mailer-count',$count+1,Carbon::now()->addMinutes(30));
        return([
            'username' => 'MAIL_USERNAME'.(string)$tail,
            'password' => 'MAIL_PASSWORD'.(string)$tail,
        ]);
    }
}
