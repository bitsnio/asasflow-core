<?php

namespace Modules\Core\Http\Requests\Core\Tenancy\TenantDomains;

use Illuminate\Foundation\Http\FormRequest;

class TenantDomainsRequest extends FormRequest
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