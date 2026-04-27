<?php

declare(strict_types=1);

namespace App\Http\Requests\API\ERP\Order;

use App\DTO\API\ERP\Order\ResolveToBeCanceledStatusDTO;
use App\Http\Requests\API\ApiRequest;
use App\Models\Enums\Order\OrderStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

final class ResolveToBeCanceledStatusRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'orderExternalId' => [
                'bail',
                'required',
                'string',
                'exists:orders,external_id',
            ],
            'timeToWait' => [
                'bail',
                'sometimes',
                'date_format:Y-m-d H:i:s',
                'after:now',
            ],
            'status' => [
                'bail',
                'required',
                Rule::in(OrderStatus::values()),
            ],
        ];
    }

    public function toDTO(): ResolveToBeCanceledStatusDTO
    {
        $data = $this->validated();

        return new ResolveToBeCanceledStatusDTO(
            orderExternalId: $data['orderExternalId'],
            status: $data['status'],
            waitUntil: isset($data['timeToWait']) ? $data['timeToWait'] : null,
        );
    }
}
