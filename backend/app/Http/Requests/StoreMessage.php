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
        $sendTo = User::find(Request('sendTo'));
        return auth('api')->check()
            && $user->info->message_limit > 0 // 用户仍然有信息余量
            && $sendTo //千万别忘了检查这个被发信人确实合法存在
            && !$sendTo->info->no_stranger_message // 对方允许接收陌生用户信息
            && $user->id != $sendTo->id ;// 并不是自己给自己发信息
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

    public function generateMessage()
    {
        $message_data['poster_id'] = auth('api')->id();
        $message_data['receiver_id'] = Request('sendTo');
        $message = DB::transaction(function() use($message_data) {
            $message_body = MessageBody::create(['body' => request('body')]);
            $message_data['message_body_id'] = $message_body->id;
            $message = Message::create($message_data);
            if (!auth('api')->user()->isAdmin()){
                auth('api')->user()->info->decrement('message_limit');
            }
            return $message;
        });
        return $message;
    }
}
