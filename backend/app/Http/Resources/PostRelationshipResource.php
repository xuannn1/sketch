<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return [
            'author'         =>  $author,
        ];
    }
}
