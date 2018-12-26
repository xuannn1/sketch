<?php

namespace App\Http\Resources\PostResources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\BookResources\ChapterProfileResource;

class PostRelationshipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ((!$this->is_anonymous)||(Auth::guard('api')->check()&&((Auth::guard('api')->user()->is_admin)||(Auth::guard('api')->id()===$this->user_id)))){
            $author = new AuthorIdentifierResource($this->author);
        }else{
            $author = [];
        }
        if ($this->is_maintext){
            $chapter = new ChapterProfileResource($this->mainchapter);
        }else{
            $chapter = [];
        }
        return [
            'author'        =>  $author,
            'chapter'       =>  $chapter,
        ];
    }
}
