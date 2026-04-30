<?php

namespace App\Http\Requests\API\Admin\BottlesDiscrepancyReason;

use App\DTO\API\Admin\BottlesDiscrepancyReason\EditDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class EditRequest extends ApiRequest
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
                'int',
                'exists:bottles_discrepancy_reasons,id',
            ],
            'reason' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function toDTO(): EditDTO
    {
        $data = $this->validated();

        return new EditDTO(
            reasonId: $data['id'],
            reason: $data['reason'],
        );
    }
}
