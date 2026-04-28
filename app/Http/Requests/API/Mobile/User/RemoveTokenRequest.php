<?php

namespace App\Http\Requests\API\Mobile\User;

use App\DTO\API\Mobile\User\RemoveTokenDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class RemoveTokenRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => [
                'bail',
                'required',
                'string',
            ],
        ];
    }

    /**
     * @return object
     */
    public function toDTO(): RemoveTokenDTO
    {
        $data = $this->validated();

        return new RemoveTokenDTO(
            $data['token'],
        );
    }
}
