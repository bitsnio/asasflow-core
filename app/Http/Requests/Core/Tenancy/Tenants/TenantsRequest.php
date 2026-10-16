<?php

namespace Modules\Core\Http\Requests\Core\Tenancy\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class TenantsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // @asasflow:rules:start
        return [];
        // @asasflow:rules:end
    }
}