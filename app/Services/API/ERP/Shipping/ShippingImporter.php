<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Shipping;

use App\DTO\API\ERP\Shipping\ShippingDTO;
use App\Http\Resources\ERP\Shipping\ShippingResource;
use App\Models\Shipping;
use App\Models\User;
use App\Services\API\ERP\ERPServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class ShippingImporter implements ERPServiceInterface
{
    public function __construct(private ShippingValidator $shippingValidator)
    {
    }

    public function applyChanges(Collection $records): ShippingResource
    {
        Log::info('Start shipping validation');
        $storedRecords = 0;
        $totalAmountOfRecords = 0;
        foreach ($records as $number => $shipping) {
            $this->processShipping($shipping, $number, $storedRecords, $totalAmountOfRecords);
        }
        Log::info('All shippings imported successfully');

        return new ShippingResource(
            [
                'amountOfStoredRecords' => $storedRecords,
                'totalAmountOfRecords' => $totalAmountOfRecords,
            ]
        );
    }

    private function processShipping(
        array $shipping,
        int $number,
        int &$storedRecords,
        int &$totalAmountOfRecords,
    ): void {
        try {
            $validShipping = $this->shippingValidator->validateShipping($shipping, $number, $totalAmountOfRecords);
            $expeditorId = User::getIdByExternalId($validShipping['expeditor_id']);

            $validShipping['expeditor_id'] = $expeditorId;
            $shippingDTO = $this->shippingValidator->transformToShippingDTO($validShipping);
        } catch (\Exception) {
            return;
        }

        $shippingExists = Shipping::forExpeditor($expeditorId)
            ->forDate($shippingDTO->date)
            ->where('time_start', $shippingDTO->time_start)
            ->exists();

        $shippingExists ? $this->updateShipping($shippingDTO) : $this->saveShipping($shippingDTO);

        $storedRecords++;
        Log::info("shipping {$shipping['expeditor_id']} imported successfully");
    }

    private function updateShipping(ShippingDTO $dto): void
    {
        $shipping = Shipping::forExpeditor($dto->expeditorId)
            ->forDate($dto->date)
            ->where('time_start', $dto->time_start)
            ->first();
        $shipping->update([
            'window_number' => $dto->window_number,
            'time_end' => $dto->time_end,
        ]);
    }

    private function saveShipping(ShippingDTO $dto): void
    {
        Shipping::query()->create([
            'date' => $dto->date,
            'expeditor_id' => $dto->expeditorId,
            'window_number' => $dto->window_number,
            'time_start' => $dto->time_start,
            'time_end' => $dto->time_end,
        ]);
    }
}
