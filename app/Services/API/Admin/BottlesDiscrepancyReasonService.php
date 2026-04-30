<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\DTO\API\Admin\BottlesDiscrepancyReason\CreateDTO;
use App\DTO\API\Admin\BottlesDiscrepancyReason\DeleteDTO;
use App\DTO\API\Admin\BottlesDiscrepancyReason\EditDTO;
use App\Http\Resources\Admin\BottlesDiscrepancyReason\CreateResource;
use App\Http\Resources\Admin\BottlesDiscrepancyReason\DeleteResource;
use App\Http\Resources\Admin\BottlesDiscrepancyReason\EditResource;
use App\Http\Resources\Admin\BottlesDiscrepancyReason\GetCollection;
use App\Models\BottlesDiscrepancyReason;

final readonly class BottlesDiscrepancyReasonService
{
    private const int PAGE_SIZE = 30;

    public function getAll(): GetCollection
    {
        return new GetCollection(
            BottlesDiscrepancyReason::query()->paginate(self::PAGE_SIZE)
        );
    }

    public function create(CreateDTO $dto): CreateResource
    {
        $reason = BottlesDiscrepancyReason::query()->create([
            'reason' => $dto->reason,
        ]);

        return new CreateResource($reason);
    }

    public function edit(EditDTO $dto): EditResource
    {
        /** @var BottlesDiscrepancyReason $reason */
        $reason = BottlesDiscrepancyReason::query()->find($dto->reasonId);
        $reason->update([
            'reason' => $dto->reason,
        ]);

        return new EditResource($reason);
    }

    public function delete(DeleteDTO $dto): DeleteResource
    {
        BottlesDiscrepancyReason::query()->where('id', $dto->reasonId)->delete();

        return new DeleteResource(['message' => 'Bottles Discrepancy Reason deleted successfully']);
    }
}
