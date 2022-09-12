<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentUser as CommentUserResource;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      // return parent::toArray($request);  // adds 'data' json wrapper
      return [
        'comment_id' => $this->id,
        'content' => $this->content,
        'created_at' => $this->created_at,
        'updated_at' => (string)$this->updated_at,
        'user' => new CommentUserResource($this->whenLoaded('user')) // relation name from comment model
      ];
    }
}
