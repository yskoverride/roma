<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class TenantFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => Str::lower($this->id),
            'company_website' => $this->company_website . "." . env("APP_DOMAIN") 
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {                        
        
        return [
            'id' => 'required|max:255|alpha_num:ascii|unique:tenants,id',
            'company_website' => ['required','max:45','unique:domains,domain',
                                    'regex:/^[A-Za-z0-9.]+$/'],
            'name' => 'required|string|max:255',
            'email' => 'required|email|string',
            'phone' => 'required|numeric|phone:mobile'
        ];
    }

    public function messages():array
    {
        return [
            'company_website.regex' => 'The :attribute may only contain letters, numbers.',
            'id.unique' => 'Another customer with same name already exists'
        ];
    }
}
