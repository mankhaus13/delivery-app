<?php

namespace App\Http\Requests\API\Admin\User;

use App\DTO\API\Admin\User\EditUserDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class EditUserRequest extends ApiRequest
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
                'exists:users,id'
            ],
            'phone' => [
                'string',
                'min:11'
            ],
            'password' => [
                'string',
                'min:6',
                'max:15'
            ],
            'first_name' => [
                'string',
                'min:2'
            ],
            'second_name' => [
                'string',
                'min:2'
            ],
            'surname' => [
                'string',
                'min:2'
            ],
        ];
    }

    public function toDTO(): EditUserDTO
    {
        $data = $this->validated();

        return new EditUserDTO(
            id: $data['id'],
            phone: $data['phone'],
            password: $data['password'] ?? '',
            first_name: $data['first_name'],
            second_name: $data['second_name'],
            surname: $data['surname'],
        );
    }
}
