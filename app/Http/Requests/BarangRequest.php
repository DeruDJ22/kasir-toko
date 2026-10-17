<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'integer', 'min:1'],
            'stok' => ['required', 'integer', 'min:0'],
            'kategori_id' => ['required', Rule::exists('kategoris', 'id')->whereNull('deleted_at')],
        ];
    }
}
