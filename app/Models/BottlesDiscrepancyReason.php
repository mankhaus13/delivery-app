<?php

namespace App\Models;

use App\Events\BottlesDiscrepancyReason\BottlesDiscrepancyReasonListChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BottlesDiscrepancyReason extends Model
{
    use HasFactory;

    protected $guarded = false;

    protected $dispatchesEvents = [
        'created' => BottlesDiscrepancyReasonListChanged::class,
        'updated' => BottlesDiscrepancyReasonListChanged::class,
        'deleted' => BottlesDiscrepancyReasonListChanged::class,
    ];
}
