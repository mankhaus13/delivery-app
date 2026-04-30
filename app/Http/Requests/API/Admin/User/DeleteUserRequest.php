<?php

namespace App\Http\Requests\API\Admin\User;

use App\DTO\API\Admin\User\DeleteUserDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class DeleteUserRequest extends ApiRequest
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
                'int',
                'exists:users,id',
            ],
        ];
    }

    public function toDTO(): DeleteUserDTO
    {
        $data = $this->validated();

        return new DeleteUserDTO(
            $data['userId'],
        );
    }
}
