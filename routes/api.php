<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

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

Route::post('auth/login', [Api\AuthController::class, 'login']);
Route::match(['post', 'get'], 'auth/azalea-response', [Api\AuthController::class, 'azaleaResponse']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('auth/user', [Api\AuthController::class, 'authData']);
    Route::post('auth/update-password', [Api\AuthController::class, 'updatePassword']);
    Route::post('auth/logout', [Api\AuthController::class, 'logout']);
    Route::resource('specialities', Api\SpecialityController::class);
    Route::resource('categories', Api\CategoryController::class);
    Route::post('remove-categories', [Api\CategoryController::class, 'deleteMultipleRecord']);
    Route::post('remove-specialities', [Api\SpecialityController::class, 'deleteMultipleRecord']);
    Route::post('our-story', [Api\OurStoryController::class, 'store']);
    
    Route::post('add-page', [Api\PageController::class, 'store']);
    Route::resource('blogs', Api\BlogController::class);
    Route::post('blogs/{id}', [Api\BlogController::class, 'update']);
    Route::post('blog-update-status/{blogId}', [Api\BlogController::class, 'updateStatus']);
    Route::post('remove-blogs', [Api\BlogController::class, 'deleteMultipleRecord']);
    Route::resource('teams', Api\TeamController::class);
    Route::post('teams/{id}', [Api\TeamController::class, 'update']);
    Route::post('teams-update-status/{teamId}', [Api\TeamController::class, 'updateStatus']);
    Route::post('remove-teams', [Api\TeamController::class, 'deleteMultipleRecord']);
    Route::resource('news', Api\NewsController::class);
    Route::post('news/{id}', [Api\NewsController::class, 'update']);
    Route::post('news-update-status/{newsId}', [Api\NewsController::class, 'updateStatus']);
    Route::resource('mental-health', Api\MentalHealthController::class);
    Route::post('mental-health/{id}', [Api\MentalHealthController::class, 'update']);
    Route::post('mental-health-update-status/{mentalHealthId}', [Api\MentalHealthController::class, 'updateStatus']);
    Route::post('remove-mental-health', [Api\MentalHealthController::class, 'deleteMultipleRecord']);
    Route::resource('faqs', Api\FaqController::class);
    Route::post('faq/{id}', [Api\FaqController::class, 'update']);
    Route::post('faq-update-status/{faqId}', [Api\FaqController::class, 'updateStatus']);
    Route::post('remove-faq', [Api\FaqController::class, 'deleteMultipleRecord']);
    Route::post('remove-news', [Api\NewsController::class, 'deleteMultipleRecord']);
    Route::resource('testing-types', Api\TestingTypeController::class);
    Route::post('remove-testing-types', [Api\TestingTypeController::class, 'deleteMultipleRecord']);
    Route::resource('age-ranges', Api\AgeRangeController::class);
    Route::post('remove-age-ranges', [Api\AgeRangeController::class, 'deleteMultipleRecord']);
    Route::resource('services-provided', Api\ServiceProvidedController::class);
    Route::post('remove-services-provided', [Api\ServiceProvidedController::class, 'deleteMultipleRecord']);
    Route::resource('states', Api\StateController::class);
    Route::resource('states-covered', Api\StateCoveredController::class);
    Route::post('remove-states-covered', [Api\StateCoveredController::class, 'deleteMultipleRecord']);
    Route::resource('users', Api\UserController::class);
    Route::post('remove-users', [Api\UserController::class, 'deleteMultipleRecord']);
    Route::resource('services', Api\ServiceController::class);
    Route::post('services/{id}', [Api\ServiceController::class, 'update']);
    Route::post('remove-services', [Api\ServiceController::class, 'deleteMultipleRecord']);
    Route::resource('roles', Api\UserRoleController::class);
    Route::resource('clinicians', Api\ClinicianController::class);
    Route::resource('testing-requests', Api\TestingRequestController::class);
    Route::resource('appointments', Api\AppointmentController::class);
    Route::post('reschedule-appointment', [Api\AppointmentController::class, 'rescheduleAppointment']);
    Route::post('update-appointment', [Api\AppointmentController::class, 'updateStatus']);
    Route::post('clinicians/{id}', [Api\ClinicianController::class, 'update']);
    Route::post('remove-clinicians', [Api\ClinicianController::class, 'deleteMultipleRecord']);
    Route::put('update-status-clinicians/{id}', [Api\ClinicianController::class, 'updateStatus']);
});
Route::get('page-data', [Api\PageController::class, 'index']);
Route::get('our-story', [Api\OurStoryController::class, 'index']);
Route::get('services', [Api\ServiceController::class, 'index']);
Route::get('faqs', [Api\FaqController::class, 'index']);
Route::get('testing-types', [Api\TestingTypeController::class, 'index']);
Route::get('age-ranges', [Api\AgeRangeController::class, 'index']);
Route::get('locations', [Api\LocationController::class, 'index']);
Route::get('clinicians', [Api\ClinicianController::class, 'index']);
Route::get('states', [Api\StateController::class, 'index']);
Route::get('services-provided', [Api\ServiceProvidedController::class, 'index']);
Route::get('specialities', [Api\SpecialityController::class, 'index']);
Route::get('services', [Api\ServiceController::class, 'index']);
Route::get('service/{id}', [Api\ServiceController::class, 'show']);
Route::get('categories', [Api\CategoryController::class, 'index']);
Route::get('blogs', [Api\BlogController::class, 'index']);
Route::get('clinician/{id}', [Api\ClinicianController::class, 'show']);
Route::get('blog/{id}', [Api\BlogController::class, 'show']);
Route::get('teams', [Api\TeamController::class, 'index']);
Route::get('team/{id}', [Api\TeamController::class, 'show']);
Route::get('news', [Api\NewsController::class, 'index']);
Route::get('news-data/{id}', [Api\NewsController::class, 'show']);
Route::get('mental-health', [Api\MentalHealthController::class, 'index']);
Route::get('mental-health-data/{id}', [Api\MentalHealthController::class, 'show']);
Route::post('book-appointment', [Api\AppointmentController::class, 'store']);
Route::get('appointment-data/{id}', [Api\AppointmentController::class, 'show']);
Route::post('add-testing-request', [Api\TestingRequestController::class, 'store']);
Route::get('slot-list', [Api\Azalea\SlotController::class, 'getSlots']);
Route::get('patient-data', [Api\Azalea\PatientController::class, 'getPatientData']);
Route::get('azalea-token', [Api\Azalea\AuthController::class, 'getAccessToken']);
Route::get('clinician-insurances', [Api\ClinicianInsuranceController::class, 'index']);
Route::get('insurance-plans/{insuranceId}', [Api\ClinicianInsuranceController::class, 'getPlans']);
Route::get('insurances', [Api\TruBillingInsuranceController::class, 'index']);
Route::get('insurance-plans', [Api\TruBillingInsurancePlanController::class, 'index']);

