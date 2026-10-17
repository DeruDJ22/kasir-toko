<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KategoriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        $kategoriId = $this->route('kategori')?->id;

        return [
            'nama' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategoris', 'nama')->ignore($kategoriId)->whereNull('deleted_at'),
            ],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ];
    }
}
