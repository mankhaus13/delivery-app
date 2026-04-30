<?php

namespace App\Http\Requests\API\Admin\CancelationReason;

use App\DTO\API\Admin\CancelationReason\DeleteCancelationReasonDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class DeleteCancelationReasonRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'exists:cancelation_reasons,id'],
        ];
    }


    public function toDTO(): DeleteCancelationReasonDTO
    {
        $data = $this->validated();

        return new DeleteCancelationReasonDTO(
            $data['id'],
        );
    }
}
