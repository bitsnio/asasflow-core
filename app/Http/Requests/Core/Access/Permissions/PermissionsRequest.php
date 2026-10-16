<?php

namespace Modules\Core\Http\Requests\Core\Access\Permissions;

use Illuminate\Foundation\Http\FormRequest;

class PermissionsRequest extends FormRequest
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