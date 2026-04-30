<?php

namespace App\Http\Requests\API\Admin\BottlesDiscrepancyReason;

use App\DTO\API\Admin\BottlesDiscrepancyReason\DeleteDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class DeleteRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'bail',
                'required',
                'exists:bottles_discrepancy_reasons,id',
            ],
        ];
    }

    public function toDTO(): DeleteDTO
    {
        $data = $this->validated();

        return new DeleteDTO(
            reasonId: $data['id'],
        );
    }
}
