<?php

namespace App\Http\Requests\API\Admin\CancelationReason;

use App\DTO\API\Admin\CancelationReason\EditCancelationReasonDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class EditCancelationReasonRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'int', 'exists:cancelation_reasons,id'],
            'reason' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDTO(): EditCancelationReasonDTO
    {
        $data = $this->validated();

        return new EditCancelationReasonDTO(
            $data['id'],
            $data['reason'],
        );
    }
}
