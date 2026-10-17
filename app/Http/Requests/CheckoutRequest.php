<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'bayar' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Keranjang masih kosong.',
            'items.*.qty.min' => 'Jumlah barang minimal 1.',
            'bayar.required' => 'Uang diterima wajib diisi.',
            'bayar.min' => 'Uang diterima tidak boleh negatif.',
        ];
    }
}
