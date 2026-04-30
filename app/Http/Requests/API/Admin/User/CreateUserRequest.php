<?php

namespace App\Http\Requests\API\Admin\User;

use App\DTO\API\Admin\User\CreateUserDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class CreateUserRequest extends ApiRequest
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
                'bail',
                'required',
                'string',
                'min:11',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:15',
            ],
            'first_name' => [
                'required',
                'string',
                'min:2',
            ],
            'second_name' => [
                'required',
                'string',
                'min:2',
            ],
            'surname' => [
                'required',
                'string',
                'min:2',
            ],
            'external_id' => [
                'bail',
                'required',
                'string',
                'unique:users,external_id',
            ],
        ];
    }

    public function toDTO(): CreateUserDTO
    {
        $data = $this->validated();

        return new CreateUserDTO(
            password: $data['password'],
            phone: $data['phone'],
            firstName: $data['first_name'],
            secondName: $data['second_name'],
            surname: $data['surname'],
            externalId: $data['external_id'],
        );
    }
}
