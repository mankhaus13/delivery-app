<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Shipping;

use App\DTO\API\ERP\Shipping\ShippingDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class ShippingValidator
{
    private function rules(): array
    {
        return [
            'date' => [
                'required',
                'date_format:d.m.Y',
            ],
            'expeditor_id' => [
                'required',
                'uuid',
                'exists:users,external_id',
            ],
            'window_number' => [
                'required',
                'string',
                'max:3',
            ],
            'time_start' => [
                'required',
                'date_format:H:i:s',
            ],
            'time_end' => [
                'required',
                'date_format:H:i:s',
            ],
        ];
    }

    public function transformToShippingDTO(array $shipping): ShippingDTO
    {
        return new ShippingDTO(
            date: $shipping['date'],
            expeditorId: $shipping['expeditor_id'],
            window_number: $shipping['window_number'],
            time_start: $shipping['time_start'],
            time_end: $shipping['time_end'],
        );
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function validateShipping(array $shipping, int $number, int &$totalAmountOfRecords): array
    {
        Log::info("Validating shipping {$shipping['expeditor_id']}");
        $totalAmountOfRecords++;
        $validator = Validator::make($shipping, $this->rules());

        if ($validator->stopOnFirstFailure()->fails()) {
            // Log validation errors for this shipping
            Log::error(
                "Validation failed for shipping at position $number with expeditor id {$shipping['expeditor_id']} ",
                ['errors' => $validator->errors()->toArray()]
            );
            throw new Exception('Validation failed'); // Skip this shipping
        }
        return $validator->validated();
    }
}
