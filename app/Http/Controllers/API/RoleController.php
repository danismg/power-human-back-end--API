<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;

class RoleController extends Controller
{
    public function create(CreateRoleRequest $request){
        try{
            // Create Role
            $role = Role::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            if(!$role){
                throw new Exception('Role not created');
            } 

            return ResponseFormatter::success($role,'Role created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(),'500');
        }
    }

    public function update(UpdateRoleRequest $request, $id){
            try {
            // Get role
            $role = Role::find($id);

            // Check if role exists
            if(!$role) {
                throw new Exception('Role not found');
            }

            // Update role
            $role->update([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($role, 'Role updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }

    }

    public function fetch(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);
        $with_responsibilities = $request->input('with_responsibilities',false);

        $roleQuery = Role::query();

        // Get single data
        // powerhuman.com/api/role?id=1                  // jika pakai if maka url akan ?id=blabla
        if($id)
        {
            // bakalan masuk ke file json
            // jika data single maka akan display responsibilitynya
            $role = $roleQuery->with('responsibilities')->find($id);  
            if($role)
            {
                return ResponseFormatter::success($role,'Role Found');
            }

            return ResponseFormatter::error('Role Not Found', 484);
        }

        // Get multiple data

        // Fileter Role dengan company id
        $roles = $roleQuery->where('company_id',$request->company_id);

        // powerhuman.com/api/role?name=Dani
        // Options/bisa ngk bisa iya
        if($name){
            $roles->where('name','like','%' . $name . '%');
        }

        // kalau dipunya responsible maka tampilkan
        if ($with_responsibilities) {
            $roles->with('responsibilities');
        }

        return ResponseFormatter::success(
            $roles->paginate($limit),
            'Roles Found'
        );
    }

    public function destroy($id){
        try {
            // Get role
            $role = Role::find($id);

            // Check if role exists
            if (!$role) {
                throw new Exception('Role not found');
            }

            // Delete role
            $role->delete();

            return ResponseFormatter::success('Role deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
