<?php

namespace App\Http\Requests\API\ERP\Bodycheck;

use App\Http\Requests\API\ERP\ERPRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection;

final class BodycheckRequest extends ERPRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => [
                'required',
                'array',
            ],
        ];
    }

    /**
     * @return Collection<int<0, max>, array>
     */
    public function getData(): Collection
    {
        return collect($this->validated()['data']);
    }
}
