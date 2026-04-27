<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Order;

use App\DTO\API\ERP\Item\ItemDTO;
use App\DTO\API\ERP\Order\OrderDTO;
use App\Models\Enums\Item\ItemType;
use App\Models\Enums\Order\OrderStatus;
use App\Models\Enums\Order\PaymentMethod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DateTime;
use Exception;
use Illuminate\Validation\ValidationException;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class OrderValidator
{
    private function rules(): array
    {
        return [
            'external_id' => [
                'required',
                'uuid',
            ],
            'expeditor_id' => [
                'required',
                'uuid',
                'exists:users,external_id',
            ],
            'return_bottles' => [
                'required',
                'integer',
            ],
            'payment_method' => [
                'required',
                'string',
                Rule::enum(PaymentMethod::class),
            ],
            'status' => [
                'required',
                'string',
                Rule::enum(OrderStatus::class),
            ],
            'period' => [
                'required',
                'string',
                Rule::enum(OrderStatus::class),
            ],
            'client_name' => [
                'required',
                'string',
            ],
            'address' => [
                'required',
                'string',
            ],
            'address_extra_info' => [
                'present',
                'nullable',
                'string',
            ],
            'shipping_date' => [
                'required',
                'date_format:d.m.Y',
            ],
            'total' => [
                'nullable',
                'decimal:2',
            ],
            'expected_delivery_time' => [
                'nullable',
                'date_format:H:i:s',
            ],
            'order_comment' => [
                'present',
                'nullable',
                'string',
            ],
            'address_comment' => [
                'present',
                'nullable',
                'string',
            ],
            'number' => [
                'required',
                'string',
                'min:10',
                'max:10',
            ],
            'items' => [
                'present',
                'array',
            ],
            'items.*.name' => [
                'string',
                'required',
            ],
            'items.*.type' => [
                'nullable',
                'string',
                Rule::enum(ItemType::class),
            ],
            'items.*.image' => [
                'required',
                'string',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
            ],
            'items.*.price' => [
                'required',
                'decimal:2',
            ]
        ];
    }

    /**
     * Customize the error messages for each field.
     *
     * @return array<string, string>
     */
    private function messages(): array
    {
        return [
            'expeditor_id.exists' => 'The selected expeditor_id :input is invalid.',
        ];
    }

    public function transformToOrderDTO(array $order): OrderDTO
    {
        $date = DateTime::createFromFormat('d.m.Y', $order['shipping_date']);
        return new OrderDTO(
            external_id: $order['external_id'],
            expeditor_id: $order['expeditor_id'],
            number: $order['number'],
            shipping_date: $date->format('Y-m-d'),
            return_bottles: $order['return_bottles'],
            payment_method: $order['payment_method'],
            status: $order['status'],
            period: $order['period'],
            client_name: $order['client_name'],
            address: $order['address'],
            address_comment: $order['address_comment'] ?? '',
            address_extra_info: $order['address_extra_info'] ?? '',
            expected_delivery_time: $order['expected_delivery_time'],
            order_comment: $order['order_comment'] ?? '',
            total: $order['total'] ? floatval($order['total']) : 0,
            items: $order['items'] !== [] ? $this->getItems($order['items']) : collect(),
        );
    }

    /**
     * @return Collection<int<0, max>, ItemDTO>
     */
    private function getItems(array $items): Collection
    {
        $itemDTOs = [];
        foreach ($items as $item) {
            $itemDTOs[] =  new ItemDTO(
                image: $item['image'],
                name: $item['name'],
                quantity: $item['quantity'],
                price: floatval($item['price']),
                type: $item['type'],
            );
        }

        return collect($itemDTOs);
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function validateOrder(array $order, int $number, &$totalAmountOfOrders): array
    {
        Log::info("Validating order {$order['external_id']}");
        $totalAmountOfOrders++;
        $validator = Validator::make($order, $this->rules(), $this->messages());

        if ($validator->stopOnFirstFailure()->fails()) {
            // Log validation errors for this order
            Log::error(
                "Validation failed for order at position $number with external id {$order['external_id']} ",
                ['errors' => $validator->errors()->toArray()]
            );
            throw new Exception('Validation failed'); // Skip this order
        }
        return $validator->validated();
    }
}
