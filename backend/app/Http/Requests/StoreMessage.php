<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Message;
use App\Models\User;
use App\Models\MessageBody;
use DB;
use Carbon\Carbon;

class StoreMessage extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'sendTo' => 'numeric',
            'sendTos' => 'array',
            'body' => 'required|string|max:20000',
        ];
    }

    public function userSend()
    {
        $this->validateSendTo(Request('sendTo'), auth('api')->id());
        $messages = $this->generateMessages([Request('sendTo')], Request('body'));
        return $messages[0];
    }

    public function adminSend()
    {
        $this->validateSendTos(Request('sendTos'), auth('api')->id());
        return $messages = $this->generateMessages(Request('sendTos'), Request('body'));
    }

    public function generateMessages($sendTos, $body)
    {
        $messages = DB::transaction(function() use($sendTos, $body){
            $messageBodyId = $this->generateMessageBody($body);
            $messages = $this->generateMessageRecords($sendTos, $messageBodyId);

            if (!auth('api')->user()->isAdmin()){
                auth('api')->user()->info->decrement('message_limit');
            }
            return $messages;
        });

        return $messages;
    }

    public function generateMessageBody($body)
    {
        $messageBody = MessageBody::create(['body' => $body]);
        return $messageBody->id;
    }

    public function generateMessageRecords($sendTos, $bodyId)
    {
        $created_at = Carbon::now();
        foreach ($sendTos as $sendTo) {
            $message_datas[] = [
                'poster_id' => auth('api')->id(),
                'receiver_id' => $sendTo,
                'message_body_id' => $bodyId,
                'created_at'=> $created_at,
            ];
        }
        Message::insert($message_datas);
        return $messages = Message::where('message_body_id', $bodyId)->get();
    }

    private function validateSendTos($sendTos, $selfId)
    {
        if(!auth('api')->user()->isAdmin()){abort(403);}
        $newSendTos = User::whereIn('id', $sendTos)
        ->where('id', '<>', $selfId)
        ->whereNull('deleted_at')
        ->select('id')
        ->get()
        ->pluck('id')
        ->toArray();
        $unavailable = array_diff($sendTos, $newSendTos);//未来可以考虑将这个信息返回？也或许不需要……
        if($unavailable){abort(404,json_encode($unavailable));}
    }

    private function validateSendTo($sendToId, $selfId)
    {
        if((!auth('api')->user()->isAdmin())
            &&(auth('api')->user()->info->message_limit <= 0)){
                abort(403,'message limit violation');
        }
        $sendToUser = User::find($sendToId);
        if(!$sendToUser){abort(404);}
        if($selfId === $sendToId){abort(403,'cannot send message to oneself');}
        if($sendToUser->info->no_stranger_message){abort(403,'receiver refuse to get message');}
    }
}
