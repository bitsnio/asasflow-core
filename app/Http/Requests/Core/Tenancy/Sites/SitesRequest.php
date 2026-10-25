<?php

namespace Modules\Core\Http\Requests\Core\Tenancy\Sites;

use Illuminate\Foundation\Http\FormRequest;

class SitesRequest extends FormRequest
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