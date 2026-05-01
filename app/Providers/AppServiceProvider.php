<?php

namespace App\Providers;

use App\Models\User;
use App\Repositories\Brigade\BrigadeRepository;
use App\Repositories\Brigade\BrigadeRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\API\Mobile\Calendar\CalendarService;
use App\Services\API\Mobile\Calendar\CalendarServiceInterface;
use App\Services\API\Mobile\Notification\NotificationService;
use App\Services\API\Mobile\Notification\NotificationServiceInterface;
use App\Services\API\Mobile\Order\OrderManager\OrderService;
use App\Services\API\Mobile\Order\OrderManager\OrderServiceInterface;
use App\Services\API\Mobile\Order\OrderStatusManager\OrderStatusManagerInterface;
use App\Services\API\Mobile\Order\OrderStatusManager\OrderStatusManagerService;
use App\Services\API\Mobile\User\Auth\AuthService;
use App\Services\API\Mobile\User\Auth\AuthServiceInterface;
use App\Services\API\Mobile\User\Token\TokenManagerInterface;
use App\Services\API\Mobile\User\Token\TokenManagerService;
use App\Services\API\Mobile\WorkDay\WorkDayService;
use App\Services\API\Mobile\WorkDay\WorkDayServiceInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Pulse\Facades\Pulse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //services
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(OrderStatusManagerInterface::class, OrderStatusManagerService::class);
        $this->app->bind(WorkDayServiceInterface::class, WorkDayService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(TokenManagerInterface::class, TokenManagerService::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
        $this->app->bind(CalendarServiceInterface::class, CalendarService::class);

        //repositories
        $this->app->bind(BrigadeRepositoryInterface::class, BrigadeRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Pulse::user(fn (User $user) => [
            'name' => $user->surname,
            'extra' => $user->phone,
        ]);
    }
}
