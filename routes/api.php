<?php

use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\UserInventoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\DetailContractController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ClaimController;
use App\Http\Controllers\Api\RequestController;
use App\Http\Controllers\Api\AcquisitionValueController;
use App\Http\Controllers\Api\DetailAcquisitionValueController;
use App\Http\Controllers\Api\DetailItemController;
use App\Http\Controllers\Api\DistributionController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\DownloadController;
use App\Http\Controllers\Api\FilterController;
use App\Models\AcquisitionValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::get('/items', [ItemController::class, 'index']);
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'getUser']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
// Route::get('/', [LoginController::class, 'login'])->name('login');
// Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

// Route::get('home', [HomeController::class, 'index'])->name('home')->middleware('auth');
// Route::get('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

Route::middleware('api')->group(function () {
    Route::resource('companies', CompanyController::class);
});
Route::get('/companies_export', [CompanyController::class, 'export']);
Route::post('/companies_import', [CompanyController::class, 'import']);

Route::middleware('api')->group(function () {
    Route::resource('items', ItemController::class);
});
Route::get('/open-image/{filename}', [ItemController::class, 'openImage']);
Route::get('/items_export', [ItemController::class, 'export']);
Route::post('/items_import', [ItemController::class, 'import']);

Route::get('/user_inventory/{id}', [UserInventoryController::class, 'getInventoryById']);

// Route::get('/companies', [CompanyController::class, 'index']);
// Route::post('/companies', [CompanyController::class, 'store']);
// Route::get('/companies/{id}', [CompanyController::class, 'show']);
// Route::put('/companies/{id}', [CompanyController::class, 'update']);
// Route::delete('/companies/{id}', [CompanyController::class, 'destroy']);

Route::get('/detail_contracts/{id}', [DetailContractController::class, 'index']);
Route::get('/detail_acquisition_values/{id}', [DetailAcquisitionValueController::class, 'index']);
Route::get('/detail_items/{id}', [DetailItemController::class, 'index']);

Route::get('/distributions', [DistributionController::class, 'index']);
Route::get('/item_counts', [DistributionController::class, 'getItemOnArea']);
Route::get('/item_per_area/{area}', [DistributionController::class, 'getItemByArea']);

Route::middleware('api')->group(function () {
    Route::resource('contracts', ContractController::class);
});
Route::get('/contracts_export', [ContractController::class, 'export']);
Route::post('/contracts_import', [ContractController::class, 'import']);

Route::get('/payment/{id}', [PaymentController::class, 'index']);
Route::post('/payment', [PaymentController::class, 'store']);
Route::put('/payment_update/{id}', [PaymentController::class, 'update']);
Route::post('/payment_import', [PaymentController::class, 'import']);
Route::get('/open-invoice/{filename}', [PaymentController::class, 'openInvoice']);

Route::middleware('api')->group(function () {
    Route::resource('claims', ClaimController::class);
});
Route::get('/claims_export', [ClaimController::class, 'export']);
Route::get('/claims_process_export', [ClaimController::class, 'claimProcessExport']);
Route::get('/open-document/{filename}', [ClaimController::class, 'openDocument']);

Route::get('/request_admin', [RequestController::class, 'getRequestAdmin']);
Route::get('/request_manager', [RequestController::class, 'getRequestManager']);
Route::get('/user_claims/{id}', [RequestController::class, 'getClaimById']);
Route::get('/user_history/{id}', [RequestController::class, 'getHistoryById']);
Route::get('/accept_request_admin/{id}', [RequestController::class, 'acceptClaimByAdmin']);
Route::get('/accept_request_manager/{id}', [RequestController::class, 'acceptClaimByManager']);
Route::get('/process_claim_admin', [RequestController::class, 'processClaimByAdmin']);
Route::get('/reject_request/{id}', [RequestController::class, 'rejectClaim']);
Route::get('/finish_claim/{id}', [RequestController::class, 'finishClaim']);

// Route::get('/acquisition_values', [AcquisitionValueController::class, 'index']);
Route::middleware('api')->group(function () {
    Route::resource('acquisition_values', AcquisitionValueController::class);
});
Route::get('/acquisition_value_export', [AcquisitionValueController::class, 'export']);
Route::post('/acquisition_value_import', [AcquisitionValueController::class, 'import']);

Route::get('/budget', [BudgetController::class, 'index']);
Route::get('/monthly_budget/{year}', [BudgetController::class, 'monthlyBudget']);
Route::get('/trend_budget/{year}', [BudgetController::class, 'trend']);

Route::get('/item_search/{keyword}', [FilterController::class, 'itemSearch']);
Route::get('/acquisition_value_search/{keyword}', [FilterController::class, 'acquisitionValueSearch']);
Route::get('/contract_search/{keyword}', [FilterController::class, 'contractSearch']);
Route::get('/company_search/{keyword}', [FilterController::class, 'companySearch']);
Route::get('/claim_search/{keyword}', [FilterController::class, 'claimSearch']);
Route::get('/request_search/{keyword}', [FilterController::class, 'requestSearch']);
Route::get('/claim_process_search/{keyword}', [FilterController::class, 'claimProcessSearch']);

Route::get('/download/{filename}', [DownloadController::class, 'index']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
