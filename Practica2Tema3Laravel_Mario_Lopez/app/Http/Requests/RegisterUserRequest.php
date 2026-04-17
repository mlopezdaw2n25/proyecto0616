<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:50',
            // Rebutjar si ja existeix un usuari verificat amb aquest email
            'email'         => 'required|email|max:50|unique:users,email',
            'password'      => 'required|min:7|max:20',
            'fitx'          => 'required|file|mimes:jpg,jpeg,png|max:2048',
            // Required for non-empresa registrations
            'tipus_user_id' => 'required_unless:tipus_type,empresa|exists:tipus_users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'tipus_user_id.required_unless' => 'Has de seleccionar un tipus d\'alumne.',
            'tipus_user_id.exists'          => 'El tipus d\'alumne seleccionat no és vàlid.',
            'fitx.required'                 => 'Has de pujar una imatge de perfil.',
            'fitx.mimes'                    => 'La imatge ha de ser JPG o PNG.',
            'fitx.max'                      => 'La imatge no pot superar els 2 MB.',
        ];
    }
}
