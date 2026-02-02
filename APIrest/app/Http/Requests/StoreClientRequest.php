<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Client;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        //return $this->user()->can('create', Client::class);
        return true;
    }

    public function rules(): array
    {
        return[
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string'],
            'address' => ['nullable', 'string']
        ];
    }
}