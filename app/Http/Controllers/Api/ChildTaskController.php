<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ChildTaskResource;
use App\Laravue\Models\ChildTask;
use Validator;
class ChildTaskController extends Controller
{
    public function index()
    {
      return ChildTaskResource::collection(ChildTask::all());
    }
    
    // public function allChildTaskNotDoneByUserId(Request $request) {
    //    try {
    //        $user = User::findOrFail($request->get('userId'));

    //        $tasks = $user->tasks;
    //      $getTasks= $tasks->where('complete_date','=',null);
    //       return TaskResource::collection($getTasks);
    //     } catch (ModelNotFoundException $exception) {
    //        return response()->json(['error'=>'Not found'],404);
    //     }
     

    // }
    public function store(Request $request)
    { 
        if(empty($request->list)) {
           $validator = Validator::make($request->all(),$this->getValidationRules());

          if($validator->fails()){
          return response()->json([
              'errors'=>$validator->errors(),
          ],403);
          }
          $newChildTask= ChildTask::create([
          'name'=> $request->get('name'),
          'necessary_time'=>$request->get('necessary_time'),
          'parent_task_id'=>$request->get('parent_task'),
          'done'=>$request->get('done'),
        ]);
        return new ChildTaskResource($newChildTask);
        } else {
            foreach($request->list as $data) {
                $validator = Validator::make($data,$this->getValidationRules());
                if($validator->fails()) {
                    return response()->json([
                        'errors'=>$validator->errors(),
                      ],403);
                }
                ChildTask::create([
                 'name'=> $data['name'],
                 'necessary_time'=>$data['necessary_time'],
                 'parent_task_id'=>$data['parent_task']
                ]);
            }

            return response()->json(['message'=>'Create success'],200);
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $childTaskDB = ChildTask::findOrFail($id);
        if ($childTaskDB ===  null){
            return response()->json(['message'=>'Not found resource'],404);
        }
        return new ChildTaskResource($childTaskDB);
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
        $childTaskDB = ChildTask::findOrFail($id);
        if($childTaskDB ===null) {
            return response()->json(['message'=>'Not Found Data'],403);
        }
        try { 
            if(!empty($request->get('name'))) {
                $childTaskDB->name = $request->get('name');
            }
            if(!empty($request->get('necessary_time'))) {
                $childTaskDB->necessary_time = $request->get('necessary_time');
            }
            if(!empty($request->get('status'))) {
                $childTaskDB->done = $request->get('status');
            }
            $childTaskDB->save();
         return response()->json(['message'=>'Update success','data'=>$childTaskDB],200);
        }catch(Exception $ex){
          return response()->json(['message'=>'Error da xay ra'],500);
        }
        // Se khong can validate neu chi update 1 truong 1. Vi vay khong can update cai nay.
        // Neu muon update truong nao thi chi can truyen du lieu vao truong day thoi
        // if($childTaskDB === null){
        //     return response()->json([
        //         'error'=>'Not Found'
        //     ],404); 
        // } else {
        //      $validator = Validator::make($request->all(),$this->getValidationRules());
        //      $childTaskDB->name = $request->get('name');
        //      if($request->get('necessary_time')!=='') {
        //         $childTaskDB->necessary_time = $request->get('necessary_time');
        //      }
             
        //      $childTaskDB->parent_task_id = $request->get('parent_task');
        //      $childTaskDB->save();
        //      return new ChildTaskResource($childTaskDB);
        // }
         
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $childTaskDB = ChildTask::findOrFail($id);
        if($childTaskDB === null){
            return response()->json([
                'error'=>'Not Found'
            ],404); 
        } else {
            $childTaskDB->delete();
            return response()->json([
                'message'=>'Delete success'
            ],200); 
        }
    }
   
    private function getValidationRules() {
        return [
            'name'=>'required',
            'necessary_time'=>'required',
            'parent_task'=>'required',
        ];
    }

}
