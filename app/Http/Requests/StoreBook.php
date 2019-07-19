<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Thread;
use Carbon;
use DB;
use StringProcess;
use App\Models\Tongren;
use App\Sosadfun\Traits\GenerateThreadDataTraits;

class StoreBook extends FormRequest
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
            'title' => 'required|string|max:20',
            'brief' => 'required|string|max:25',
            'body' => 'string|nullable|max:25',
            'channel_id' => 'required|numeric|min:1|max:2',
            'primary_tag' => 'numeric|min:1',
            'book_status_tag' => 'numeric|min:1',
            'book_length_tag' => 'numeric|min:1',
            'sexual_orientation_tag' => 'numeric|min:1',
            'is_bianyuan' =>'required',
            'majia' => 'string|max:10',
            'tongren_yuanzhu' => 'string|nullable|max:60',
            'tongren_CP' => 'string|nullable|max:60',
        ];
    }

    public function generateBook($channel)
    {
        $thread_data = $this->generateThreadData($channel);

        $thread_data['download_as_book']=$this->download_as_book ? true:false;
        $thread_data['download_as_thread']=$this->download_as_thread ? true:false;

        $thread = Thread::create($thread_data);

        return $thread;
    }

    public function updateBookProfile($thread)
    {
        $thread_data = $this->generateUpdateThreadData($thread);
        $thread_data['is_bianyuan']=$this->is_bianyuan==1? true:false;

        $thread->update($thread_data);
        return $thread;
    }
}
