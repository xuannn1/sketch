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
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|string|max:20000|min:10',
        ];
    }

    public function generateMessage()
    {
        $message['poster_id'] = auth('api')->id();
        $message['receiver_id'] = Request()->route('user')->id;
        $message = DB::transaction(function() use($message) {
            $message['message_body_id'] = DB::table('message_bodies')->insertGetId(['body' => request('body')]);
            $message = Message::create($message);
            return $message;
        });
        return $message;
    }
}
