<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function all(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);

        // jika ada if maka ?condition if  = blabla

        // powerhuman.com/api/company?id=1
        if($id)
        {
            // $company = Company::find($id);                   Tanpa ada relasi
            $company = Company::with(['users'])->find($id);   //Pakai relasi

            if($company)
            {
                return ResponseFormatter::success($company,'Company Found');
            }

            return ResponseFormatter::error('Company Not Found', 484);
        }
        //Ambil data company si user
        // powerhuman.com/api/company
        $companies = Company::with(['users']);

        // powerhuman.com/api/company?name=Dani
        if($name){
            $companies->where('name','like','%' . $name . '%');
        }

        // Atau jika mau disatuin jadi satu line
        // $companies = Company::with(['users'])->where('name','like','%' . $name . '%');

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Companies Found'
        );
    }
    
}
