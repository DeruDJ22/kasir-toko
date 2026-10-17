<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $karyawanId = $this->route('karyawan')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($karyawanId)],
            'nip' => ['required', 'string', 'max:20', Rule::unique('users', 'nip')->ignore($karyawanId)],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
        ];
    }
}
