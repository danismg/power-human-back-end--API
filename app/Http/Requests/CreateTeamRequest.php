<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();           // ngecheck login or not
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'  =>   'required|string|max:255',
            // Validation relasi => companies, id => ada tidak companynya
            // Attach yang ada dicreate dipindahkan
            'company_id' => 'required|integer|exists:companies,id'
        ];
    }
}
