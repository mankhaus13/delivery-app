<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Brigade;

use App\DTO\API\ERP\Brigade\BrigadeDTO;
use App\Http\Resources\ERP\Brigade\BrigadeResource;
use App\Models\Brigade;
use App\Models\BrigadeMember;
use App\Models\User;
use App\Services\API\ERP\ERPServiceInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class BrigadeImporter implements ERPServiceInterface
{
    public function __construct(private BrigadeValidator $brigadeValidator)
    {
    }

    /**
     * @throws Exception
     */
    public function applyChanges(Collection $records): BrigadeResource
    {
        Log::info('Start brigade validation');
        $storedRecords = 0;
        $totalAmountOfRecords = 0;
        foreach ($records as $number => $brigade) {
            $this->processBrigade($brigade, $number, $storedRecords, $totalAmountOfRecords);
        }
        Log::info('All brigades imported successfully');

        return new BrigadeResource(
            [
                'amountOfStoredRecords' => $storedRecords,
                'totalAmountOfRecords' => $totalAmountOfRecords
            ]
        );
    }

    /**
     * @throws Exception
     */
    private function processBrigade(
        array $brigade,
        int $number,
        int &$storedRecords,
        int &$totalAmountOfRecords,
    ): void {
        try {
            $brigadeDTO = $this->brigadeValidator->transformToBrigadeDTO(
                $this->brigadeValidator->validateBrigade($brigade, $number, $totalAmountOfRecords)
            );
        } catch (Exception) {
            //пропускаем невалидные записи
            return;
        }

        $expeditorId = User::query()->where('external_id', $brigadeDTO->expeditorId)->value('id');
        $brigadeExists = Brigade::forExpeditor($expeditorId)
            ->forDate($brigadeDTO->date)
            ->exists();

        $brigadeExists ?
            $this->updateBrigade($brigadeDTO, $expeditorId) :
            $this->saveBrigade($brigadeDTO, $expeditorId);

        $storedRecords++;
        Log::info("Brigade {$brigade['external_id']} imported successfully");
    }

    /**
     * @throws Exception
     */
    private function updateBrigade(BrigadeDTO $dto, int $expeditorId): void
    {
        //транзакция для консистентности и для запуска ивента после сохранения brigade_members records
        DB::beginTransaction();
        try {
            /** @var Brigade $brigade */
            $brigade = Brigade::forExpeditor($expeditorId)
                ->forDate($dto->date)
                ->first();

            $brigade->update([
                'expeditor_id' => $expeditorId,
                'date' => $dto->date,
                'car_id' => $dto->carId,
                'period' => $dto->period,
            ]);

            BrigadeMember::query()->where('brigade_id', $brigade->id)->delete();

            $this->saveMembers($dto, $brigade->id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function saveBrigade(BrigadeDTO $dto, int $expeditorId): void
    {
        //транзакция для консистентности и для запуска ивента после сохранения brigade_members records
        DB::beginTransaction();
        try {
            /** @var Brigade $brigade */
            $brigade = Brigade::query()->create([
                'expeditor_id' => $expeditorId,
                'date' => $dto->date,
                'car_id' => $dto->carId,
                'period' => $dto->period,
            ]);

            $this->saveMembers($dto, $brigade->id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            throw new Exception('Internal error occurred', 0, $e);
        }
    }

    private function saveMembers(BrigadeDTO $dto, int $brigadeId): void
    {
        foreach ($dto->members as $member) {
            BrigadeMember::query()->create([
                'brigade_id' => $brigadeId,
                'fio' => $member->fio,
                'telephone' => $member->telephone,
                'position' => $member->position,
            ]);
        }
    }
}
