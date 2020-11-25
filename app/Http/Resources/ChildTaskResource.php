<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\Task;
class ChildTaskResource extends JsonResource
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
           'name'=>$this->name,
           'necessary_time'=>$this->necessary_time, 
           'parent_task'=>$this->task,
        ];
    }
}
