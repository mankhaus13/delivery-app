<?php

declare(strict_types=1);

namespace App\Actions\API\Mobile\WorkDay;

use App\DTO\API\Mobile\WorkDay\GetWorkDayDTO;
use App\Http\Resources\Mobile\WorkDay\WorkDayCollection;
use App\Models\Bodycheck;
use App\Models\Brigade;
use App\Models\Order;
use App\Models\Shipping;
use App\Repositories\Brigade\BrigadeRepository;
use Carbon\Carbon;
use DateTime;

final readonly class GetInfoAction
{
    public function __construct(private BrigadeRepository $brigadeRepository)
    {
    }

    public function getInfo(GetWorkDayDTO $dto): WorkDayCollection
    {
        $expeditorId = $dto->expeditorId;
        $date = date('Y-m-d', strtotime($dto->date)); //enforce date format
        $brigadeId = $this->brigadeRepository->getBrigadeIdByUserId($date, $expeditorId);

        return new WorkDayCollection([
            'data' => [
                'shipping' => $this->getShipping($date, $expeditorId),
                'bodycheck' => $this->getBodycheck($date, $expeditorId),
                'orderStatistic' => $this->getOrderStatistic($date, $expeditorId),
                'brigadeMembers' => $brigadeId ? $this->getBrigadeMembers($brigadeId) : [],
                'car' => $brigadeId ? ['number' => Brigade::query()->where('id', $brigadeId)->value('car_id')] : [],
                'dayOrderStatistic' => $this->getDayOrderStatistic($date, $expeditorId),
            ],
        ]);
    }

    //TODO: возможно надо вынести в сервис Shipping, так как другая зона ответственности
    private function getShipping(string $date, int $expeditorId): array
    {
        $shippings = Shipping::query()
            ->forDate($date)
            ->forExpeditor($expeditorId)
            ->select([
                'id',
                'window_number',
                'date',
                'time_start',
                'time_end',
            ])
            ->get();
        if (! $shippings) {
            return [];
        }

        return $shippings->map(static function (Shipping $shipping) {
            $date = DateTime::createFromFormat('Y-m-d', $shipping->date)->format('d.m.Y');
            $dateTimeStart = DateTime::createFromFormat('H:i:s', $shipping->time_start)->format('H:i:s');
            $datetime = $date . ' ' . $dateTimeStart;

            return [
                'id' => $shipping->id,
                'window' => $shipping->window_number,
                'datetime' => $datetime,
            ];
        })->toArray();
    }

    private function getBodycheck(string $date, int $expeditorId): array
    {
        $bodycheck = Bodycheck::query()
            ->forDate($date)
            ->forExpeditor($expeditorId)
            ->first();
        if (! $bodycheck) {
            return [];
        }

        //пройденный медосмотр не показываем
        if ((bool) $bodycheck->passed) {
            return [];
        }

        return [
            'id' => $bodycheck->id,
            'datetime' => Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $bodycheck->date . ' ' . $bodycheck->time_start
            )->format('d.m.Y H:i:s'),
        ];
    }

    private function getOrderStatistic(string $date, int $expeditorId): array
    {
        return [
            'delivered' => Order::countDelivered($date, $expeditorId),
            'current' => Order::countCurrent($date, $expeditorId),
            'closed' => Order::countCanceled($date, $expeditorId),
        ];
    }

    private function getBrigadeMembers(int $brigadeId): array
    {
        return $this->brigadeRepository->getMembersByBrigadeId($brigadeId)->toArray();
    }

    private function getDayOrderStatistic(string $date, int $expeditorId): array
    {
        return [
            'emptyBottles' => Order::countEmptyBottles($date, $expeditorId),
            'ordersCount' => Order::countAll($date, $expeditorId),
            'totalAmount' => Order::countTotal($date, $expeditorId),
            'returnBottles' => Order::countReturnBottles($date, $expeditorId),
            'cash' => 0,
            'actsOfDiscrepancy' => Order::countActsOfDiscrepancy($date, $expeditorId),
        ];
    }
}
