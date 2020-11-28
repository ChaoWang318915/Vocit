<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            "content" => $this->content,
            "lg_url" => $this->lg_url,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
