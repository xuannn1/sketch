<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\Status;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use DB;

class StoreStatus extends FormRequest
{
    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return auth('api')->check();
        //未来需要考虑限制一个用户短时间内连续发送多条状态
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            'body' => 'required|string|max:190',
        ];
    }

    public function generateStatus()
    {

        $status = $this->only('body');
        $status['user_id'] = auth('api')->id();
        if ($this->has('reply_to_status_id')&&(!empty(Status::find($this->reply_to_status_id)))){
            $status['reply_to_status_id'] = $this->reply_to_status_id;
        }
        if (!$this->isDuplicateStatus($status)){
            $status = DB::transaction(function () use($status) {
                $status = Status::create($status);
                return $status;
            });
        }else{
            abort(409);
        }
        return $status;
    }

    public function isDuplicateStatus($status)
    {
        $last_status = Status::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_status)) && (strcmp($last_status->body, $status['body']) === 0);
    }

    /**
    * Handle a failed validation attempt.
    *
    * @param  \Illuminate\Contracts\Validation\Validator  $validator
    * @return void
    *
    * @throws \Illuminate\Validation\ValidationException
    */

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->error($validator->errors(), 422));
    }
}
