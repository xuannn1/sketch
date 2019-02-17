<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Status;
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

        $status_data = $this->only('body');
        $status_data['user_id'] = auth('api')->id();
        if ($this->has('reply_id')&&(!empty(Status::find($this->reply_id)))){
            $status_data['reply_id'] = $this->reply_id;
        }
        if (!$this->isDuplicateStatus($status_data)){
            $status = DB::transaction(function () use($status_data) {
                $status = Status::create($status_data);
                return $status;
            });
        }else{
            abort(409);
        }
        return $status;
    }

    public function isDuplicateStatus($status_data)
    {
        $last_status = Status::where('user_id', auth('api')->id())
        ->orderBy('created_at', 'desc')
        ->first();
        return (!empty($last_status)) && (strcmp($last_status->body, $status_data['body']) === 0);
    }
}
