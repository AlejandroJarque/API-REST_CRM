<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'min:1'],
            'client_id' => ['required', 'integer', 'exists:clients,id'],
            'title' => ['required', 'string', 'min:1'],
            'status' => ['required', 'in:' . implode(',', Activity::STATUSES)],
            'date' => ['required', 'date'],
        ];
    }
}