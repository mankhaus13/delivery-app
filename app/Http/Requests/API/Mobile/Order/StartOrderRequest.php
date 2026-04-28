<?php

namespace App\Http\Requests\API\Mobile\Order;

use App\DTO\API\Mobile\Order\StartOrderDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

final class StartOrderRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['bail', 'required', 'int', 'exists:orders,id'],
        ];
    }

    public function toDTO(): StartOrderDTO
    {
        $data = $this->validated();

        return new StartOrderDTO(
            orderId: $data['id'],
            expeditorId: Auth::id()
        );
    }
}
