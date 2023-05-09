<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Responsibility;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;

class ResponsibilityController extends Controller
{
    public function create(CreateResponsibilityRequest $request){
        try{
            // Create Responsibility
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            if(!$responsibility){
                throw new Exception('Responsibility not created');
            } 

            return ResponseFormatter::success($responsibility,'Responsibility created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(),'500');
        }
    }

    public function fetch(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);

        $responsibilityQuery = Responsibility::query();

        // Get single data
        // powerhuman.com/api/responsibility?id=1
        if($id)
        {
            $responsibility = $responsibilityQuery->find($id);  
            if($responsibility)
            {
                return ResponseFormatter::success($responsibility,'Responsibility Found');
            }

            return ResponseFormatter::error('Responsibility Not Found', 484);
        }

        // Get multiple data

        // Fileter Responsibility dengan role id
        $responsibilities = $responsibilityQuery->where('role_id',$request->role_id);

        // powerhuman.com/api/responsibility?name=Dani
        // Options/bisa ngk bisa iya
        if($name){
            $responsibilities->where('name','like','%' . $name . '%');
        }
        return ResponseFormatter::success(
            $responsibilities->paginate($limit),
            'Responsibilities Found'
        );
    }

    public function destroy($id){
        try {
            // Get responsibility
            $responsibility = Responsibility::find($id);

            // Check if responsibility exists
            if (!$responsibility) {
                throw new Exception('Responsibility not found');
            }

            // Delete responsibility
            $responsibility->delete();

            return ResponseFormatter::success('Responsibility deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
