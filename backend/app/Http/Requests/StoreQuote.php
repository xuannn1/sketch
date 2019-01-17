<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest;
use App\Models\Quote;
use DB;

class StoreQuote extends FormRequest
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
            'body' => 'required|string|max:80|unique:quotes',
            'majia' => 'string|max:10',
        ];
    }

    public function generateQuote()
    {
        $quote = $this->only('body');
        $quote['user_id'] = auth('api')->id();
        if ($this->is_anonymous){
            $quote['is_anonymous'] = 1;
            $quote['majia'] = $this->majia;
        } else{
            $quote['is_anonymous'] = 0;
        }
        $quote = DB::transaction(function() use($quote) {
            $quote = Quote::create($quote);
            return $quote;
        });
        return $quote;
    }
}
