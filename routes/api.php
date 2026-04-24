<?php

use App\Http\Controllers\API\Admin\BodycheckController;
use App\Http\Controllers\API\Admin\BottlesDiscrepancyReasonController as AdminDiscrepancyController;
use App\Http\Controllers\API\Admin\BrigadeController;
use App\Http\Controllers\API\Admin\CancelationReasonController;
use App\Http\Controllers\API\Admin\ItemController;
use App\Http\Controllers\API\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\API\Admin\ShippingController;
use App\Http\Controllers\API\Admin\UserController as AdminUserController;
use App\Http\Controllers\API\ERP\BodycheckController as ERPBodycheckController;
use App\Http\Controllers\API\ERP\BrigadeController as ERPBrigadeController;
use App\Http\Controllers\API\ERP\NotificationController as ERPNotificationController;
use App\Http\Controllers\API\ERP\OrderController as ERPOrderController;
use App\Http\Controllers\API\ERP\ShippingController as ERPShippingController;
use App\Http\Controllers\API\Mobile\CalendarController;
use App\Http\Controllers\API\Mobile\CancelationReasonController as MobileCancelationReasonController;
use App\Http\Controllers\API\Mobile\DiscrepancyReasonController as MobileDiscrepancyReasonController;
use App\Http\Controllers\API\Mobile\NotificationController as MobileNotificationController;
use App\Http\Controllers\API\Mobile\OrderController;
use App\Http\Controllers\API\Mobile\UserController as MobileUserController;
use App\Http\Controllers\API\Mobile\WorkDayController;
use App\Http\Middleware\Authenticate;
use App\Models\Enums\User\UserRole;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\API\Admin\OrderController as AdminOrderController;
use \App\Http\Middleware\EnsureUserHasRole;

/**
 * так как используется сессионная авторизация, стремящаяся редиректнуть на роут логин,
 * сделал здесь возврат ошибки, вместо того, чтоб в каждом запросе добавлять заголовок Accept: application\json
 */
Route::get('unauth', static function () {
    return response()->json(['message' => 'Unauthenticated'], Response::HTTP_UNAUTHORIZED);
})->name('login');

/**
 * mobile
 */
Route::group(
    ['prefix' => 'mobile'],
    static function () {
        Route::post('/auth', [MobileUserController::class, 'auth']);
        Route::get('/check-auth', [MobileUserController::class, 'checkAuth']);

        Route::middleware([
            Authenticate::class,
            EnsureUserHasRole::class . ':' . implode(',', [
                UserRole::Developer->value,
                UserRole::Expeditor->value,
            ])
        ])->group(
                static function () {
                    Route::post('/logout', [MobileUserController::class, 'logout']);

                    Route::get('/orders', [OrderController::class, 'getAll']);
                    Route::patch('/order-submit', [OrderController::class, 'complete']);
                    Route::patch('/order-cancel', [OrderController::class, 'cancel']);
                    Route::patch('/order-start', [OrderController::class, 'start']);
                    Route::get('/active-order', [OrderController::class, 'getActive']);

                    Route::get('/calendar', CalendarController::class);
                    Route::get('/work-day', WorkDayController::class);
                    Route::patch('/read-notification', [MobileNotificationController::class, 'markAsRead']);
                    Route::get('/notifications', [MobileNotificationController::class, 'getAll']);
                    Route::get('/cancel-reasons', [MobileCancelationReasonController::class, 'getAll']);

                    Route::post('/token/add', [MobileUserController::class, 'addToken']);
                    Route::post('/token/delete', [MobileUserController::class, 'removeToken'])
                        ->withoutMiddleware([Authenticate::class, 'role:developer,expeditor']);

                    Route::get('/discrepancy-reasons', [MobileDiscrepancyReasonController::class, 'getAll']);
                }
            );
    }
);

/**
 * admin part
 */
Route::group(
    ['prefix' => 'admin'],
    static function () {
        Route::post('/auth', [AdminUserController::class, 'auth']);
        Route::get('/check-auth', [AdminUserController::class, 'checkAuth']);

        Route::middleware([
            Authenticate::class,
            EnsureUserHasRole::class . ':' . implode(',', [
                UserRole::Developer->value,
                UserRole::Admin->value,
            ])
        ])->group(
                static function () {
                    Route::get('/bodychecks', [BodycheckController::class, 'getAll']);
                    Route::get('/brigades', [BrigadeController::class, 'getAll']);
                    Route::get('/items', [ItemController::class, 'getAll']);
                    Route::get('/notifications', [AdminNotificationController::class, 'getAll']);
                    Route::get('/shippings', [ShippingController::class, 'getAll']);

                    Route::get('/orders', [AdminOrderController::class, 'getAll']);
                    Route::patch('/orders', [AdminOrderController::class, 'edit']);

                    Route::get('/cancelation-reasons', [CancelationReasonController::class, 'getAll']);
                    Route::post('/cancelation-reason', [CancelationReasonController::class, 'create']);
                    Route::patch('/cancelation-reason', [CancelationReasonController::class, 'edit']);
                    Route::delete('/cancelation-reason', [CancelationReasonController::class, 'delete']);

                    Route::get('/users', [AdminUserController::class, 'getAll']);
                    Route::post('/user', [AdminUserController::class, 'create']);
                    Route::patch('/user', [AdminUserController::class, 'edit']);
                    Route::delete('/user', [AdminUserController::class, 'delete']);
                    Route::get('/user/logs', [AdminUserController::class, 'getLogs']);

                    Route::get('/discrepancy-reasons', [AdminDiscrepancyController::class, 'getAll']);
                    Route::post('/discrepancy-reason', [AdminDiscrepancyController::class, 'create']);
                    Route::patch('/discrepancy-reason', [AdminDiscrepancyController::class, 'edit']);
                    Route::delete('/discrepancy-reason', [AdminDiscrepancyController::class, 'delete']);
                }
            );
    }
);

/**
 * erp
 */
Route::group(
    ['prefix' => 'erp'],
    static function () {

        Route::post('/order', [ERPOrderController::class, 'applyChanges']);
        Route::patch('/order/wait-client', [ERPOrderController::class, 'waitClient']);
        Route::patch('/order/resolve-cancel-status', [ERPOrderController::class, 'resolveToBeCanceledStatus']);

        Route::post('/bodycheck', [ERPBodycheckController::class, 'applyChanges']);

        Route::post('/brigade', [ERPBrigadeController::class, 'applyChanges']);

        Route::post('/shipping', [ERPShippingController::class, 'applyChanges']);

        Route::post('/notification', [ERPNotificationController::class, 'applyChanges']);
    }
);
