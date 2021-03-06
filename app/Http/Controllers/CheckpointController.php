<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkpoint;
use Validator;

class CheckpointController extends Controller
{
    public function show(Checkpoint $checkpoint) {

        return response()->json(['checkpoint' => $checkpoint]);
        
    }

    public function index() {
        
        $checkpoints = Checkpoint::get();
        
        return response()->json([
            'checkpoints' => $checkpoints
        ]);

    }

    public function updateOne(Request $request) {

        $validator = Validator::make($request->all(), [
            'checkpoint' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $checkpoint = (object)$request->checkpoint;


        $checkpointToUpdate = Checkpoint::findOrFail($checkpoint->id);
        
        //update its data
        $checkpointToUpdate->description = $checkpoint->description;
        $checkpointToUpdate->status = $checkpoint->status;
        $checkpointToUpdate->claimed_by = $checkpoint->claimed_by;
        
        //save it to db
        $checkpointToUpdate->save();
        
        return response()->json([
            'checkpoint' => $checkpointToUpdate,
        ]);
    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'checkpoints' => 'required',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $checkpoints = (object)$request->checkpoints;

        $checkpointsToUpdate = [];

        // $checkpointToUpdate = Checkpoint::findOrFail($checkpoint->id);
        
        foreach ($checkpoints as $key => $checkpoint) {
            $checkpoint = (object)$checkpoint;
            
            //fin the checkpoint by id
            $checkpointToUpdate = Checkpoint::findOrFail($checkpoint->id);
            
            //update its data
            $checkpointToUpdate->description = $checkpoint->description;
            $checkpointToUpdate->status = $checkpoint->status;
            $checkpointToUpdate->claimed_by = $checkpoint->claimed_by;
            
            //save it to db
            $checkpointToUpdate->save();

            //put into array to give as response
            $checkpointsToUpdate[$key] = $checkpointToUpdate;
        }

        return response()->json([
            'checkpoints' => $checkpointsToUpdate,
        ]);
    }

    public function destroy($id) {

        Checkpoint::findOrFail($id)->delete();

        return response()->json([
            'ok'
        ]);
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'checkpoints' => 'required',
            'task_id' => 'required|exists:tasks,id',
        ]);
      
        if($validator->fails()) {
            return response()->json([
                    'message' => $validator->errors()
                ],
                422
            );
        }

        $checkpoints = (object)$request->checkpoints;

        $task_id = $request->task_id;

        $newCheckpoints = [];

        foreach ($checkpoints as $key => $checkpoint) {
            $checkpoint = (object)$checkpoint;
            $newCheckpoint = Checkpoint::create([
                'description' => $checkpoint->description,
                'status' => 0,
                'task_id' => $task_id,
            ]);
            $newCheckpoints[$key] = $newCheckpoint;
        }

        return response()->json([
            'checkpoints' => $newCheckpoints,
        ]);
    }
}