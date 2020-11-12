<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'id'=>$this->id,
            'title'=>$this->title,
            'start_date'=>$this->start_date,
            'finish_date'=>$this->finish_date,
            'user'=>$this->user,
            'group'=>$this->group,
        ];
    }
}
