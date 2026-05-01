<?php

namespace App\Providers;

use App\Events\BottlesDiscrepancyReason\BottlesDiscrepancyReasonListChanged;
use App\Events\CancelationReason\CancelationReasonListChanged;
use App\Events\Notification\NotificationCreated;
use App\Events\Order\OrderSaved;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderUpdating;
use App\Events\WorkDay\WorkDayChanged;
use App\Listeners\BottlesDiscrepancyReason\SendViaMQTT as BottlesDiscrepancySendViaMQTT;
use App\Listeners\CancelationReason\SendViaMQTT as CancelationReasonMqtt;
use App\Listeners\Notification\SendPush;
use App\Listeners\Notification\SendViaMQTT as NotificationMqtt;
use App\Listeners\Order\ActiveOrderChanged;
use App\Listeners\Order\SendToERP;
use App\Listeners\Order\SendViaMQTT as OrderMQTT;
use App\Listeners\WorkDay\SendViaMQTT as WorkDayMQTT;
use App\Models\Order;
use App\Observers\OrderObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        NotificationCreated::class => [
            SendPush::class,
            NotificationMqtt::class,
        ],
        CancelationReasonListChanged::class => [
            CancelationReasonMqtt::class,
        ],
        OrderSaved::class => [
            OrderMqtt::class,
        ],
        OrderUpdated::class => [
            SendToERP::class,
            OrderMqtt::class,
        ],
        OrderUpdating::class => [
            ActiveOrderChanged::class,
        ],
        WorkDayChanged::class => [
            WorkDayMQTT::class,
        ],
        BottlesDiscrepancyReasonListChanged::class => [
            BottlesDiscrepancySendViaMQTT::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Order::observe(OrderObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
