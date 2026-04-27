<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Bodycheck;

use App\DTO\API\ERP\Bodycheck\BodycheckDTO;
use App\Http\Resources\ERP\Bodycheck\BodycheckResource;
use App\Models\Bodycheck;
use App\Models\User;
use App\Services\API\ERP\ERPServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class BodycheckImporter implements ERPServiceInterface
{
    public function __construct(private BodycheckValidator $bodycheckValidator)
    {
    }

    public function applyChanges(Collection $records): BodycheckResource
    {
        Log::info('Start bodycheck validation');
        $storedRecords = 0;
        $totalAmountOfRecords = 0;
        foreach ($records as $number => $bodycheck) {
            $this->processBodycheck($bodycheck, $number, $storedRecords, $totalAmountOfRecords);
        }
        Log::info('All bodychecks imported successfully');

        return new BodycheckResource(
            [
            'amountOfStoredRecords' => $storedRecords,
            'totalAmountOfRecords' => $totalAmountOfRecords,
            ]
        );
    }

    private function processBodycheck(
        array $bodycheck,
        int $number,
        int &$storedRecords,
        int &$totalAmountOfRecords,
    ): void {
        try {
            $validBodycheck = $this->bodycheckValidator
                ->validateBodycheck($bodycheck, $number, $totalAmountOfRecords);

            $expeditorId = User::query()
                ->where('external_id', $validBodycheck['expeditor_id'])
                ->value('id');

            $validBodycheck['expeditor_id'] = $expeditorId;
        } catch (\Exception) {
            return;
        }

        $bodycheckDTO = $this->bodycheckValidator->transformToBodycheckDTO($validBodycheck);
        $bodycheckExists = Bodycheck::query()
            ->where('expeditor_id', $expeditorId)
            ->where('date', $bodycheckDTO->date)
            ->exists();

        $bodycheckExists ? $this->updateBodycheck($bodycheckDTO) : $this->saveBodycheck($bodycheckDTO);
        $storedRecords++;
        Log::info("Bodycheck {$bodycheck['expeditor_id']} imported successfully");
    }

    private function updateBodycheck(BodycheckDTO $dto): void
    {
        $bodycheck = Bodycheck::forExpeditor($dto->expeditorId)
            ->forDate($dto->date)
            ->first();

        $bodycheck->update([
            'time_start' => $dto->timeStart,
            'passed' => $dto->passed,
        ]);
    }

    private function saveBodycheck(BodycheckDTO $dto): void
    {
        Bodycheck::query()->create([
            'time_start' => $dto->timeStart,
            'date' => $dto->date,
            'expeditor_id' => $dto->expeditorId,
            'passed' => $dto->passed,
        ]);
    }
}
