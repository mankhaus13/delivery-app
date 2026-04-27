<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Bodycheck;

use App\DTO\API\ERP\Bodycheck\BodycheckDTO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;
use DateTime;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class BodycheckValidator
{
    private function rules(): array
    {
        return [
            'starts_at' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            'expeditor_id' => [
                'required',
                'uuid',
                'exists:users,external_id',
            ],
            'passed' => [
                'required',
                'boolean',
            ],
        ];
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function validateBodycheck(array $bodycheck, int $number, int &$totalAmountOfRecords): array
    {
        Log::info("Validating bodycheck {$bodycheck['expeditor_id']}");
        $totalAmountOfRecords++;
        $validator = Validator::make($bodycheck, $this->rules());

        if ($validator->stopOnFirstFailure()->fails()) {
            // Log validation errors for this bodycheck
            Log::error(
                "Validation failed for bodycheck at position $number with expeditor id {$bodycheck['expeditor_id']} ",
                ['errors' => $validator->errors()->toArray()]
            );
            throw new Exception('Validation failed'); // Skip this bodycheck
        }
        return $validator->validated();
    }

    public function transformToBodycheckDTO(array $data): BodycheckDTO
    {
        return new BodycheckDTO(
            expeditorId: $data['expeditor_id'],
            timeStart: (new DateTime())
                ->createFromFormat('Y-m-d H:i:s', $data['starts_at'])
                ->format('H:i:s'),
            date: (new DateTime())
                ->createFromFormat('Y-m-d H:i:s', $data['starts_at'])
                ->format('Y-m-d'),
            passed: $data['passed'],
        );
    }
}
