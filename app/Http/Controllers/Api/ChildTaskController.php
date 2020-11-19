<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ChildTaskResource;
use App\Laravue\Models\ChildTask;
use Validator;
class ChildTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return ChildTaskResource::collection(ChildTask::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
      ]);
      return new ChildTaskResource($newChildTask);
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
        if($childTaskDB === null){
            return response()->json([
                'error'=>'Not Found'
            ],404); 
        } else {
             $validator = Validator::make($request->all(),$this->getValidationRules());
             $childTaskDB->name = $request->get('name');
             if($request->get('necessary_time')!=='') {
                $childTaskDB->necessary_time = $request->get('necessary_time');
             }
             
             $childTaskDB->parent_task_id = $request->get('parent_task');
             $childTaskDB->save();
             return new ChildTaskResource($childTaskDB);
        }
         
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
            'parent_task_id'=>'required',
        ];
    }
}
