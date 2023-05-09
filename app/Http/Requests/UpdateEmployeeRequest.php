<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();       // ngecheck login or not
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'      =>  'nullable|string|max:255',
            'email'     =>  'nullable|string|email|max:255',
            'gender'    =>  'nullable|string|in:MALE,FEMALE',
            'age'       =>  'nullable|integer',
            'phone'     =>  'nullable|string|max:255',
            'photo'     =>  'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'role_id'   =>  'nullable|integer|exists:roles,id',
            'teams_id'  =>  'nullable|integer|exists:teams,id'
        ];
    }
}
