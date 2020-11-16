<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GroupResource;
use App\Laravue\Models\Group;
class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      GroupResource::withoutWrapping();

      $params= $request->all();
      $keyword = Arr::get($params,'keyword');
      if(!empty($keyword)) 
       {   
       $query=Group::query();
       $query->where('name','LIKE','%'.$keyword.'%');
       
       return GroupResource::collection($query->get());
       }  
       return GroupResource::collection(Group::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $valid =Validator::make($request->all(),[
           'name'=>'|required|max:255'
       ]);

       if($valid->fails()){
           return response()->json(['message'=>'Name of group is required'],403);
       }
       
       $newGroup= Group::create($request->only('name'));
       $list_user=$request->list_userId;
       $newGroup->users()->attach($list_user);
       return response()->json(['message'=>'Create success','data'=>new GroupResource($newGroup)],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::findOrFail($id);
        if(!$group){
            return response()->json(['message'=>'Not Found'],404);
        }
        // return new GroupResource($group);
        // return response()->json([
        //     "id"=>$group->id,
        //     "name"=>$group->name,
        //     "users"=>$group->users,-
        // ]);

        return response()->json([
            'data'=>new GroupResource($group),
            'users_list'=>$group->users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $valid =Validator::make($request->all(),[
           'name'=>'|required|max:255'
       ]);

       if($valid->fails()){
           return response()->json(['message'=>'Name of group is required'],403);
       }

       $group = Group::find($id);
       $group->name = $request->name;
       
       $group->save();
       //tai day cung co the su dung detach($list_id) cu sau do attach list_id moi vao
       //Hay thu voi phuong thuc $group ->roles()->sync([1,2,3]); =>giong nhu detach; syncWithoutDetaching([1,2,3])=>se chi luu gia tri nay tai bang trung gian
      // thi se chi luu nhung gia tri moi nay va khong luu them nhung gia tri khac
        DB::table('users_groups')->where('group_id',$group->id)->delete();
       $group->users()->attach($request->list_userId);
       return response()->json(['message'=>'Update success'],201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function forceDelete(Request $request){
        $list_id = $request->list_id;
        $result = Group::destroy($list_id);
        if($result==0) {
            return response()->json(['message'=>'Delete Nothing'],200);
        }

        return response()->json([
            'message'=>'Delete success',
            'data'=> GroupResource::collection(Group::all()),
        ],200);
    }
}
