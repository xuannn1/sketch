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
        $sendTo = $this->getSendTo();
        $basic_validation = auth('api')->check() && $sendTo; // 用户登录且被发信人确实合法存在

        if($user->isAdmin()){ // 管理员群发私信验证
            return $basic_validation;
        }else{ // 用户发私信验证
            return $basic_validation
                && !is_array(Request('sendTo')) // 即用户没有试图群发私信
                && $user->info->message_limit > 0 // 用户仍然有信息余量
                && !$sendTo->info->no_stranger_message // 对方允许接收陌生用户信息
                && $user->id != $sendTo->id; // 并不是自己给自己发信息
        }
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

    public function generateMessage($sendTo)
    {
        $message_data['poster_id'] = auth('api')->id();
        $message_data['receiver_id'] = $sendTo;
        $message = DB::transaction(function() use($message_data) {
            if(!$message_body = MessageBody::where('body', Request('body'))->first()){
                $message_body = MessageBody::create(['body' => Request('body')]);
            }
            $message_data['message_body_id'] = $message_body->id;
            $message = Message::create($message_data);
            if (!auth('api')->user()->isAdmin()){
                auth('api')->user()->info->decrement('message_limit');
            }
            return $message;
        });
        return $message;
    }

    public function userSend()
    {
        return $this->generateMessage(Request('sendTo'));
    }

    public function adminSend()
    {
        $sendTos = $this->getSendTo()->toArray();
        if($sendTos != Request('sendTo')){
            return ;
        }
        foreach ($sendTos as $sendTo) {
            $messages[] = $this->generateMessage($sendTo);
        }
        return collect($messages);
    }

    public function getSendTo()
    {
        if(auth('api')->user()->isAdmin()){
            return $sendTo = User::whereIn('id', Request('sendTo'))
                ->where('id', '<>', auth('api')->id())
                ->whereNull('deleted_at')
                ->select('id')
                ->get()
                ->pluck('id');
        }else{
            return $sendTo = User::find(Request('sendTo'));
        }
    }
}
