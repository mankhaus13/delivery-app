<?php

namespace App\Http\Requests\API\Admin\User;

use App\DTO\API\Admin\User\GetLogDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class GetLogRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'userId' => [
                'bail',
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function toDTO(): GetLogDTO
    {
        $data = $this->validated();

        return new GetLogDTO(
            expeditorId: $data['userId'],
        );
    }
}
