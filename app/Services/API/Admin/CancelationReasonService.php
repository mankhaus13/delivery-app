<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\DTO\API\Admin\CancelationReason\CreateCancelationReasonDTO;
use App\DTO\API\Admin\CancelationReason\DeleteCancelationReasonDTO;
use App\DTO\API\Admin\CancelationReason\EditCancelationReasonDTO;
use App\Http\Resources\Admin\CancelationReason\CancelationReasonCollection;
use App\Http\Resources\Admin\CancelationReason\CreateCancelationReasonResource;
use App\Http\Resources\Admin\CancelationReason\DeleteCancelationReasonResource;
use App\Http\Resources\Admin\CancelationReason\EditCancelationReasonResource;
use App\Models\CancelationReason;

final readonly class CancelationReasonService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): CancelationReasonCollection
    {
        return new CancelationReasonCollection(CancelationReason::query()->paginate(self::PAGE_SIZE));
    }

    public function create(CreateCancelationReasonDTO $dto): CreateCancelationReasonResource
    {
        $cancelationReason = CancelationReason::query()->create([
            'reason' => $dto->reason,
        ]);

        return new CreateCancelationReasonResource($cancelationReason);
    }

    public function edit(EditCancelationReasonDTO $dto): EditCancelationReasonResource
    {
        $cancelationReason = CancelationReason::query()->find($dto->id); //проверка на существование - в реквесте
        $cancelationReason->update([
            'reason' => $dto->reason,
        ]);

        return new EditCancelationReasonResource($cancelationReason);
    }

    public function delete(DeleteCancelationReasonDTO $dto): DeleteCancelationReasonResource
    {
        CancelationReason::query()->where('id', $dto->id)->delete();

        return new DeleteCancelationReasonResource(['message' => 'Cancelation Reason deleted successfully']);
    }
}
