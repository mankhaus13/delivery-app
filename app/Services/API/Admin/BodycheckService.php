<?php

declare(strict_types=1);

namespace App\Services\API\Admin;

use App\Http\Resources\Admin\Bodycheck\BodycheckCollection;
use App\Models\Bodycheck;

final readonly class BodycheckService
{
    private const int PAGE_SIZE = 30; //записей на страницу

    public function getAll(): BodycheckCollection
    {
        return new BodycheckCollection(Bodycheck::with('expeditor')->paginate(self::PAGE_SIZE));
    }
}
