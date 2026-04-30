<?php

namespace App\Http\Requests\API\Admin\CancelationReason;

use App\DTO\API\Admin\CancelationReason\CreateCancelationReasonDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class CreateCancelationReasonRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:255'],
        ];
    }

    public function toDTO(): CreateCancelationReasonDTO
    {
        $data = $this->validated();

        return new CreateCancelationReasonDTO(
            $data['reason'],
        );
    }
}
