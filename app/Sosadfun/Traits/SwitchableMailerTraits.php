<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon;

use Mail;
use Swift_Mailer;
use Swift_SmtpTransport;
use Exception;

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
    public function send_email_to_select_server($view, $data, $to, $subject)
    {
        $from = env('MAIL_USERNAME','null');
        $name = env('MAIL_NAME','null');

        $mail_setting = $this->select_server();

        // Setup your gmail mailer
        $transport = new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
        $transport->setUsername(env($mail_setting['username']));
        $transport->setPassword(env($mail_setting['password']));
        // Any other mailer configuration stuff needed...
        $gmail = new Swift_Mailer($transport);

        // Set the mailer as gmail
        Mail::setSwiftMailer($gmail);

        try {
            Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
                $message->from($from, $name)->to($to)->subject($subject);
            });
        } catch (Exception $ex) {
            return redirect('/')->with('error', '邮件服务暂不可用，请等待系统空闲时再行尝试');
        }
    }
}
