<?php

namespace App\Http\Requests\API\Mobile\Order;

use App\DTO\API\Mobile\Order\GetOrdersDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class GetOrdersRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'date' => [
                'bail',
                'required',
                'date_format:d.m.Y',
                'before:tomorrow',
            ],
        ];
    }

    public function toDTO(): GetOrdersDTO
    {
        $data = $this->validated();

        return new GetOrdersDTO(
            date: date('Y-m-d', strtotime($data['date'])),
            expeditorId: (int)Auth::id(),
        );
    }
}
