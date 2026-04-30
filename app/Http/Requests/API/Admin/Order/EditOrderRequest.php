<?php

namespace App\Http\Requests\API\Admin\Order;

use App\DTO\API\Admin\Order\EditOrderDTO;
use App\Http\Requests\API\ApiRequest;
use Illuminate\Contracts\Validation\ValidationRule;

final class EditOrderRequest extends ApiRequest
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
            'id' => [
                'required',
                'int',
                'exists:orders,id'
            ],
            'expeditor_id' => [
                'required',
                'int',
                'exists:users,id',
            ],
            'address' => [
                'required',
                'string',
            ],
            'returnBottles' => [
                'required',
                'int',
            ],
            'emptyBottles' => [
                'required',
                'int',
            ],
        ];
    }

    public function toDTO(): EditOrderDTO
    {
        $data = $this->validated();

        return new EditOrderDTO(
            id: $data['id'],
            expeditorId: $data['expeditor_id'],
            address: $data['address'],
            returnBottles: $data['returnBottles'],
            emptyBottles: $data['emptyBottles'],
        );
    }
}
