<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DropDownController;
use App\Http\Controllers\Admin\ExpenseAccountController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ExpenseTypeController;
use App\Http\Controllers\Admin\PartyController;
use App\Http\Controllers\Admin\PaymentReceiveController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Admin Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('drop-down')->group(function () {
        Route::get('/expense_types', [DropDownController::class, 'expense_types']);
        Route::get('/expense_accounts', [DropDownController::class, 'expense_accounts']);
        Route::get('/firms', [DropDownController::class, 'firms']);
        Route::get('/bill_types', [DropDownController::class, 'bill_types']);
        Route::get('/currencies', [DropDownController::class, 'currencies']);
        Route::get('/parties', [DropDownController::class, 'parties']);
        Route::get('/products', [DropDownController::class, 'products']);
        Route::get('/payment_types', [DropDownController::class, 'payment_types']);
        Route::post('/bills', [DropDownController::class, 'bills']);
    });
    Route::apiResource('expense', ExpenseController::class);
    Route::get('/expense-types', [ExpenseTypeController::class, 'listExpenseTypes']);
    Route::post('/expense-types', [ExpenseTypeController::class, 'storeExpenseTypes']);
    Route::apiResource('party', PartyController::class);
    Route::apiResource('product', ProductController::class);
    Route::apiResource('sale', SaleController::class);
    Route::post('/create-sale-invoice-id', [SaleController::class, 'createSaleInvoiceId']);
    Route::get('/payment-receive', [PaymentReceiveController::class, 'index']);
    Route::post('/payment-receive', [PaymentReceiveController::class, 'store']);
    Route::delete('/payment-receive/{id}', [PaymentReceiveController::class, 'destroy']);

    // Dashboard
    Route::get('/report/general', [DashboardController::class, 'getGeneralReport']);
    Route::get('/report/payment-recieved-by-reciever', [DashboardController::class, 'getPaymentRecievedByReciever']);

    // Settings
    Route::get('/setting', [SettingController::class, 'show']);
    Route::post('/update-setting', [SettingController::class, 'update']);
});

Route::match(['get', 'post'], '/invoice/pdf/{id?}', [SaleController::class, 'generateInvoicePDF']);
Route::match(['post'], '/multi-invoice/pdf', [SaleController::class, 'generateMultipleInvoicePDF']);
Route::match(['get', 'post'], '/report/{type}', [ReportController::class, 'generateReportPDF']);
