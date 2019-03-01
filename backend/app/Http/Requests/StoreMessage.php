<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Message;
use App\Models\User;
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
        $user = auth()->user();
        $sendTo = User::find(Request('sendTo'));
        return (auth('api')->check()) && ($user->message_limit > 0) && (!$sendTo->no_stranger_messages) && ($user->id != $sendTo->id);
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
            $message_data['message_body_id'] = DB::table('message_bodies')->insertGetId(['body' => request('body')]);
            $message = Message::create($message_data);
            if (!auth('api')->user()->isAdmin()){
                auth('api')->user()->decrement('message_limit');
            }
            return $message;
        });
        return $message;
    }
}
