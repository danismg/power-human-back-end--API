<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function create(CreateEmployeeRequest $request){
        try{
            
            // Upload photo
            if($request->hasFile('photo')){
                // nah kalau punya photo langsung simpan ke store
                $path = $request->file('photo')->store('public/photo');
            }

            // Create Employee
            $employee = Employee::create([
                'name' => $request->name,
                'email'=>$request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone'=>$request->phone,
                'photo' => $path,
                'role_id' => $request->role_id,
                'team_id' => $request->team_id,
            ]);

            if(!$employee){
                throw new Exception('Employee not created');
            } 

            return ResponseFormatter::success($employee,'Employee created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(),'500');
        }
    }

    public function update(UpdateEmployeeRequest $request, $id){
            try {
            // Get employee
            $employee = Employee::find($id);

            // Check if employee exists
            if(!$employee) {
                throw new Exception('Employee not found');
            }

            // Upload photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photo');
            }

            // Update employee
            $employee->update([
                'name' => $request->name,
                // kalau misal photonya ngk diisinya maka diambil photo sebelumnya
                'photo' => isset($path) ? $path :$employee->photo,
                'company_id' => $request->company_id,
            ]);

            return ResponseFormatter::success($employee, 'Employee updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }

    }

    public function fetch(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $age = $request->input('age');
        $phone = $request->input('phone');
        $team_id = $request->input('team_id');
        $role_id = $request->input('role_id');
        $limit = $request->input('limit',10);

        $employeeQuery = Employee::query();

        // Get single data
        // powerhuman.com/api/employee?id=1                  // jika pakai if maka url akan ?id=blabla
        if($id)
        {
            $employee = $employeeQuery->with(['team','role'])->find($id);  
            if($employee)
            {
                return ResponseFormatter::success($employee,'Employee Found');
            }

            return ResponseFormatter::error('Employee Not Found', 484);
        }

        // Get multiple data

        // Fileter Employee dengan company id
        $employees = $employeeQuery;

        // powerhuman.com/api/employee?name=Dani
        if($name){
            $employees->where('name','like','%' . $name . '%');
        }

        if ($age) {
            $employees->where('age', $age);
        }

        if($email){
            $employees->where('email',$email);
        }

        if ($role_id) {
            $employees->where('role_id', $role_id);
        }

        if ($team_id) {
            $employees->where('team_id', $team_id);
        }

        return ResponseFormatter::success(
            $employees->paginate($limit),
            'Employees Found'
        );
    }

    public function destroy($id){
        try {
            // Get employee
            $employee = Employee::find($id);

            // Check if employee exists
            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // Delete employee
            $employee->delete();

            return ResponseFormatter::success('Employee deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
