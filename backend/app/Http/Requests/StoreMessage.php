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
            'body' => 'required|string|max:20000',
        ];
    }

    public function generateMessage()
    {
        $message_data['poster_id'] = auth('api')->id();
        $message_data['receiver_id'] = Request()->route('user')->id;
        $message = DB::transaction(function() use($message_data) {
            $message_data['message_body_id'] = DB::table('message_bodies')->insertGetId(['body' => request('body')]);
            $message = Message::create($message_data);
            return $message;
        });
        return $message;
    }
}
