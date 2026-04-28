<?php

namespace App\Http\Requests\API\Mobile\Order;

use App\DTO\API\Mobile\Order\CompleteOrderDTO;
use App\Http\Requests\API\ApiRequest;
use App\Rules\DiscrepancyReasonIdValidationRule;
use Illuminate\Contracts\Validation\ValidationRule;

final class CompleteOrderRequest extends ApiRequest
{
    /**
     * Indicates if the validator should stop on the first rule failure.
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'int',
                'exists:orders,id',
            ],
            'emptyBottles' => [
                'required',
                'int',
            ],
            'discrepancyReasonId' => [
                'present',
                new DiscrepancyReasonIdValidationRule(),
            ],
        ];
    }

    public function toDTO(): CompleteOrderDTO
    {
        $data = $this->validated();

        return new CompleteOrderDTO(
            orderId: $data['id'],
            emptyBottles: $data['emptyBottles'],
            discrepancyReasonId: $data['discrepancyReasonId'],
        );
    }
}
