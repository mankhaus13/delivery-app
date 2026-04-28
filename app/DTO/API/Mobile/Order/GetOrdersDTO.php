<?php

namespace App\DTO\API\Mobile\Order;

use App\DTO\API\BaseAPIDTO;

/**
 * DTO for retrieving orders.
 *
 * @property string $date The date for which orders are to be retrieved.
 * @property int $expeditorId The ID of the expeditor associated with the orders.
 */
final readonly class GetOrdersDTO extends BaseAPIDTO
{
    /**
     * Create a new GetOrdersDTO instance.
     *
     * @param  string  $date  The date for which orders are to be retrieved.
     * @param  int  $expeditorId  The ID of the expeditor associated with the orders.
     */
    public function __construct(
        public string $date,
        public int $expeditorId,
    ) {
    }
}
