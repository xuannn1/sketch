<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Thread;
use Carbon;
use DB;
use StringProcess;
use App\Sosadfun\Traits\GenerateThreadDataTraits;


class StoreThread extends FormRequest
{

    use GenerateThreadDataTraits;

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
            'channel_id' => 'required|numeric',
            'title' => 'required|string|max:30',
            'brief' => 'required|string|max:50',
            'body' => 'required|string|min:10|max:20000',
            'majia' => 'string|max:10',
        ];
    }
    public function generateThread($channel)
    {

        $thread_data = $this->generateThreadData($channel);
        $thread = Thread::create($thread_data);
        return $thread;
    }

    public function updateThread(Thread $thread)
    {
        $thread_data = $this->generateUpdateThreadData($thread);
        $thread->update($thread_data);
        return $thread;
    }
}
