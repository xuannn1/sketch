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
            || (!$user->isAdmin() // 用户发私信的验证
            && $user->info->message_limit > 0 // 普通用户仍然有信息余量
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

    public function generateMessage($sendTo, $message_body = null)
    {
        $message_data['poster_id'] = auth('api')->id();
        $message_data['receiver_id'] = $sendTo;
        $message = DB::transaction(function() use($message_data, $message_body) {
            if(!$message_body){
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
        $sendTo = User::find(Request('sendTo'));

        if($sendTo
        && auth('api')->id() != $sendTo->id // 不能给自己发私信
        && !$sendTo->info->no_stranger_message){ // 对方允许接收陌生用户信息
            return $this->generateMessage(Request('sendTo'));
        }

        return ;
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
            return ;
        }

        DB::beginTransaction();
        try{
            $message_body = MessageBody::create(['body' => Request('body')]);

            foreach ($sendTos as $sendTo) {
                $messages[] = $this->generateMessage($sendTo, $message_body);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }

        return collect($messages);
    }
}
