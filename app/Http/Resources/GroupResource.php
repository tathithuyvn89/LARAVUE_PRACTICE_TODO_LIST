<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
class GroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {  
        $user_listId=DB::table('users_groups')->where('group_id',$this->id)->pluck('user_id');
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'users'=>$user_listId,
        ];  
    }
}
