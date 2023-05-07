<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{
    public function create(CreateTeamRequest $request){
        try{
            
            // Upload icon
            if($request->hasFile('icon')){
                // nah kalau punya icon langsung simpan ke store
                $path = $request->file('icon')->store('public/icon');
            }

            // Create Team
            $team = Team::create([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            if(!$team){
                throw new Exception('Team not created');
            } 

            return ResponseFormatter::success($team,'Team created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(),'500');
        }
    }

    public function update(UpdateTeamRequest $request, $id){
            try {
            // Get team
            $team = Team::find($id);

            // Check if team exists
            if(!$team) {
                throw new Exception('Team not found');
            }

            // Upload icon
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icon');
            }

            // Update team
            $team->update([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($team, 'Team updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }

    }

    public function fetch(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);

        $teamQuery = Team::query();

        // Get single data
        // powerhuman.com/api/team?id=1                  // jika pakai if maka url akan ?id=blabla
        if($id)
        {
            $team = $teamQuery->find($id);  
            if($team)
            {
                return ResponseFormatter::success($team,'Team Found');
            }

            return ResponseFormatter::error('Team Not Found', 484);
        }

        // Get multiple data

        // Fileter Team dengan company id
        $teams = $teamQuery->where('company_id',$request->company_id);

        // powerhuman.com/api/team?name=Dani
        // Options/bisa ngk bisa iya
        if($name){
            $teams->where('name','like','%' . $name . '%');
        }
        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Teams Found'
        );
    }

    public function destroy($id){
        try {
            // Get team
            $team = Team::find($id);

            // Check if team exists
            if (!$team) {
                throw new Exception('Team not found');
            }

            // Delete team
            $team->delete();

            return ResponseFormatter::success('Team deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
