<?php

namespace App\Http\Requests\API\Mobile\User;

use App\DTO\API\Mobile\User\AddTokenDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class AddTokenRequest extends ApiRequest
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

    public function toDTO(): AddTokenDTO
    {
        $data = $this->validated();

        return new AddTokenDTO(
            token: $data['token'],
            expeditorId: Auth::id(),
        );
    }
}
