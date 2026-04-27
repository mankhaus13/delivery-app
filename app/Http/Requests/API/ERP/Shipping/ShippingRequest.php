<?php

namespace App\Http\Requests\API\ERP\Shipping;

use App\DTO\API\ERP\Shipping\ShippingDTO;
use App\Http\Requests\API\ERP\ERPRequest;
use Generator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection;

final class ShippingRequest extends ERPRequest
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
     * @return Collection<int<0, max>, ShippingDTO>
     */
    public function getData(): Collection
    {
        return collect($this->validated()['data']);
    }
}
