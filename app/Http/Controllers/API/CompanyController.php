<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    public function fetch(Request $request){
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit',10);

        // jika ada if maka ?condition if  = blabla

        // powerhuman.com/api/company?id=1
        if($id)
        {
            // $company = Company::find($id);                  Tanpa ada relasi
            $company = Company::with(['users'])->find($id);  // Pakai relasi tapi hanya id doang yang di cek
            $company = Company::whereHas('users', function($query){
            $query->where('user_id', Auth::id());})->with(['users'])->find($id);  
            
            

            if($company)
            {
                return ResponseFormatter::success($company,'Company Found');
            }

            return ResponseFormatter::error('Company Not Found', 484);
        }
        //Ambil data company si user
        // powerhuman.com/api/company
        
        // Blum Auth
        // $companies = Company::with(['users']);

        // Pakai Auth
        $companies = Company::whereHas('users', function($query){
            $query->where('user_id', Auth::id());
        });
            


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
    
    public function create(CreateCompanyRequest $request){

        try{
            // Ini adalah sebuah kondisi yang mengecek apakah ada file logo yang dikirimkan oleh user melalui $request. Fungsi hasFile() akan mengembalikan nilai true jika ada file logo dan false jika tidak ada.
            // Upload logo
            if($request->hasFile('logo')){
                // nah kalau punya logo langsung simpan ke store
                $path = $request->file('logo')->store('public/logo');
            }

            // Create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path
            ]);

            if(!$company){
                throw new Exception('Company not created');
            }

            // Attach company to user cause relationship many to many
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            // Load users at company
            // Nah nanti ini masuk ke $company yang ada di responseformatter
            $company->load('users');

            return ResponseFormatter::success($company,'Company created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(),'500');
        }
    }

    public function update(UpdateCompanyRequest $request, $id){
        try{

            // Get Company
            $company = Company::find($id);

            // Check if company exists
            if(!$company){
                throw new Exception('Company not found');
            }
            
            // Upload logo
            if($request->hasFile('logo')){
                $path = $request->file('logo')->store('public/logo');
            }

            // Create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path
            ]);

            return ResponseFormatter::success($company,'Company created');
        }catch(Exception $e){
            return ResponseFormatter::error($e->getMessage(),'500');
        }
    }

}
