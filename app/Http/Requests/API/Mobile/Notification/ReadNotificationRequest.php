<?php

namespace App\Http\Requests\API\Mobile\Notification;

use App\DTO\API\Mobile\Notification\ReadNotificationDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class ReadNotificationRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'exists:notifications,id'],
        ];
    }

    public function toDTO(): ReadNotificationDTO
    {
        $data = $this->validated();

        return new ReadNotificationDTO(
            $data['id'],
        );
    }
}
