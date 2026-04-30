<?php

namespace App\Http\Requests\API\Admin\BottlesDiscrepancyReason;

use App\DTO\API\Admin\BottlesDiscrepancyReason\CreateDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class CreateRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function toDTO(): CreateDTO
    {
        $data = $this->validated();

        return new CreateDTO(
            reason: $data['reason'],
        );
    }
}
