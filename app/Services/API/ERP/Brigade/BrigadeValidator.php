<?php

declare(strict_types=1);

namespace App\Services\API\ERP\Brigade;

use App\DTO\API\ERP\Brigade\BrigadeDTO;
use App\DTO\API\ERP\Member\MemberDTO;
use App\Models\Enums\Order\Period;
use App\Models\Enums\User\UserPosition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Exception;
use DateTime;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final readonly class BrigadeValidator
{
    private function rules(): array
    {
        return [
            'external_id' => [
                'required',
                'uuid',
                'exists:users,external_id',
            ],
            'date' => [
                'required',
                'date_format:d.m.Y',
            ],
            'period' => [
                'required',
                'string',
                Rule::enum(Period::class),
            ],
            'car_id' => [
                'required',
                'string',
                'max:6'
            ],
            'members.*.member_id' => [
                'required',
                'uuid',
            ],
            'members.*.position' => [
                'required',
                Rule::enum(UserPosition::class),
            ],
            'members.*.fio' => [
                'required',
                'string',
            ],
            'members.*.telephone' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function validateBrigade(array $brigade, int $number, int &$totalAmountOfRecords): array
    {
        Log::info("Validating brigade {$brigade['external_id']}");
        $totalAmountOfRecords++;
        $validator = Validator::make($brigade, $this->rules());

        if ($validator->stopOnFirstFailure()->fails()) {
            // Log validation errors for this brigade
            Log::error(
                "Validation failed for brigade at position $number with external id {$brigade['external_id']}",
                ['errors' => $validator->errors()->toArray()]
            );
            throw new Exception('Validation failed'); // Skip this brigade
        }
        return $validator->validated();
    }

    public function transformToBrigadeDTO(array $brigade): BrigadeDTO
    {
        $date = DateTime::createFromFormat('d.m.Y', $brigade['date']);

        return new BrigadeDTO(
            expeditorId: $brigade['external_id'],
            date: $date->format('Y-m-d'),
            period: $brigade['period'],
            carId: $brigade['car_id'],
            members: $this->getMembers($brigade['members']),
        );
    }

    /**
     * @return Collection<int<0, max>, MemberDTO>
     */
    private function getMembers(array $members): Collection
    {
        $memberDTOs = [];
        foreach ($members as $member) {
            $memberDTOs[] = new MemberDTO(
                fio: $member['fio'],
                telephone: $member['telephone'],
                position: $member['position'],
            );
        }
        return collect($memberDTOs);
    }
}
