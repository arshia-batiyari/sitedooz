<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\FindingSeverity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAuditRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'severity' => ['required', Rule::enum(FindingSeverity::class)],
            'is_active' => ['nullable', 'boolean'],
            'recommendation' => ['required', 'string', 'max:2000'],
        ];
    }
}
