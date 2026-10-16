<?php

namespace Modules\Core\Http\Requests\Core\Configuration\Settings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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