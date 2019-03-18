<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Message;
use App\Models\User;
use App\Models\MessageBody;
use DB;

class StoreMessage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth('api')->user();
        return auth('api')->check()
            && ($user->isAdmin() //管理员群发私信的验证
            || ($user->info->message_limit > 0 // 普通用户仍然有信息余量
            && is_int(Request('sendTo')))); // 用户没有试图群发私信
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string|max:20000',
        ];
    }

    public function userSend()
    {
        $sendTo = User::find(Request('sendTo'));

        if($sendTo
        && auth('api')->id() != $sendTo->id // 不能给自己发私信
        && !$sendTo->info->no_stranger_message){ // 对方允许接收陌生用户信息
            $message = $this->generateMessage(array(Request('sendTo')), Request('body'));
            return $message[0];
        }

        return abort(403);
    }

    public function adminSend()
    {
        $sendTos = User::whereIn('id', Request('sendTos'))
            ->where('id', '<>', auth('api')->id())
            ->whereNull('deleted_at')
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();
        if($sendTos != Request('sendTos')){
            return abort(403);
        }

        return $messages = $this->generateMessage($sendTos, Request('body'));
    }

    public function generateMessage($sendTos, $body)
    {
        $messages = DB::transaction(function() use($sendTos, $body){
            $message_body = $this->generateMessageBody($body);
            $messages = $this->generateMessageRecord($sendTos, $message_body);

            if (!auth('api')->user()->isAdmin()){
                auth('api')->user()->info->decrement('message_limit');
            }
            return $messages;
        });

       return $messages;
    }

    public function generateMessageBody($body)
    {
        return $message_body = MessageBody::create(['body' => $body]);
    }

    public function generateMessageRecord($sendTos, $body)
    {
        foreach ($sendTos as $sendTo) {
            $message_datas[] = array('poster_id' => auth('api')->id(),
                'receiver_id' => $sendTo,
                'message_body_id' => $body->id);
        }
        Message::insert($message_datas);
        return $messages = Message::where('message_body_id', $body->id)->get();
    }
}
