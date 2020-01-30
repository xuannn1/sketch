<?php
namespace App\Sosadfun\Traits;

use Cache;
use Carbon;

use Mail;
use Swift_Mailer;
use Swift_SmtpTransport;
use Exception;
use Log;

trait SwitchableMailerTraits{
    public function select_server()
    {
        $count = Cache::get('switchable-mailer-count')?? 0;
        $tail = $count % env('SWITCHABLE_MAIL_SERVERS','1');
        Cache::put('switchable-mailer-count',$count+1, 5);
        return([
            'username' => 'MAIL_USERNAME'.(string)$tail,
            'password' => 'MAIL_PASSWORD'.(string)$tail,
        ]);
    }
    public function send_email_to_select_server($view, $data, $to, $subject)
    {
        $from = env('MAIL_USERNAME','no_reply@sosad.fun');
        $name = env('MAIL_NAME','sosad_no_reply');

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
            $mail_status = Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
                $message->from($from, $name)->to($to)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::emergency('Mail'.$e->getMessage());
            abort(550);
        }
    }
    public function send_email_from_ses_server($view, $data, $to, $subject)
    {
        $name = env('MAIL_FROM_NAME','sosad_no_reply');
        $from = env('MAIL_FROM_ADDRESS','no_reply@sosad.fun');
        try {
            $mail_status = Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
                $message->from($from, $name)->to($to)->subject($subject);
            });
        } catch (\Exception $e) {
            Log::emergency('Mail'.$e->getMessage());
            abort(550);
        }
    }
}
