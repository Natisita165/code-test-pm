<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordRequest extends FormRequest
{
    public function authorize()
    {
        return true;  
    }

    public function rules()
    {
        $uuid = $this->route('record'); 

        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:100|unique:test,code' . ($uuid ? ",$uuid,uuid" : ''),
            'status' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'code.required' => 'El código es obligatorio.',
            'code.unique' => 'El código ya está en uso.',
            'status.required' => 'El estado es obligatorio.',
            'status.boolean' => 'El estado debe ser verdadero o falso.',
        ];
    }
}