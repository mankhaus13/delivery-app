<?php

namespace App\Rules;

use App\Models\BottlesDiscrepancyReason;
use App\Models\Order;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class DiscrepancyReasonIdValidationRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $orderId = request()->input('id');
        $emptyBottles = request()->input('emptyBottles');

        /** @var Order $order */
        $order = Order::query()->find($orderId);
        // если наблюдаем расхождение по бутылям, при этом причина недовоза не указана, хотим уронить валидацию
        if ($order->hasDiscrepancy($emptyBottles) && $value === null) {
            $fail("The $attribute field is required when there is a discrepancy in bottles.");
            return;
        }
        $reasonExists = BottlesDiscrepancyReason::query()->where('id', $value)->exists();
        if ($order->hasDiscrepancy($emptyBottles) && !$reasonExists) {
            $fail("The selected id $value is invalid");
        }
    }
}
