<?php

namespace App\Http\Requests\API\Admin\User;

use App\DTO\API\Mobile\User\AuthDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class AuthRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => [
                'required',
                'string'
            ],
            'password' => [
                'required',
                'string'
            ],
        ];
    }

    public function toDTO(): AuthDTO
    {
        $data = $this->validated();

        return new AuthDTO(
            $data['phone'],
            $data['password'],
        );
    }
}
