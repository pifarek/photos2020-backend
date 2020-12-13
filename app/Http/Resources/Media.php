<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Media extends JsonResource
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
            'filename' => $this->filename,
            'category' => new CategorySingleResource($this->category),
            'tags' => Tag::collection($this->tags),
            'name' => $this->name,
            'description' => $this->description,
            'takenAt' => $this->taken_at,
            'type' => $this->type,
            'photo' => new Photo($this->photo),
            'youtube' => new Photo($this->youtube),
        ];
    }
}
