<?php

namespace App\Http\Requests\API\ERP\Notification;

use App\DTO\API\ERP\Notification\NotificationDTO;
use App\Http\Requests\API\ERP\ERPRequest;
use App\Models\Enums\Notification\NotificationOrderStatus;
use Generator;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

final class NotificationRequest extends ERPRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => [
                'required',
                'array'
            ],
        ];
    }

    /**
     * @return Collection<int<0, max>, NotificationDTO>
     */
    public function getData(): Collection
    {
        return collect($this->validated()['data']);
    }
}
