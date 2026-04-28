<?php

namespace App\Http\Requests\API\Mobile\Order;

use App\DTO\API\Mobile\Order\CancelOrderDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class CancelOrderRequest extends ApiRequest
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
                'exists:orders,id'
            ],
            'reasonId' => [
                'bail',
                'required',
                'int',
                'exists:cancelation_reasons,id'
            ],
        ];
    }

    public function toDTO(): CancelOrderDTO
    {
        $data = $this->validated();

        return new CancelOrderDTO(
            $data['id'],
            $data['reasonId'],
        );
    }
}
