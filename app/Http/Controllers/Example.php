<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Employee;
use Illuminate\Http\Request;

class Example extends Controller
{
    public function index(){

        try{
            $employees = Employee::all();
            return ResponseFormatter::success($employees,'Ini adalah data employees');
        }
        catch(\Throwable $th){
            return ResponseFormatter::error(null, 'maaf data tidak ada', 500);
        }
    }
}
