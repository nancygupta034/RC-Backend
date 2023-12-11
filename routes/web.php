<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
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
Route::post('add-referral', [Api\ReferralController::class, 'store']);
Route::get('appointment-data/{id}', [Api\AppointmentController::class, 'show']);
Route::post('add-testing-request', [Api\TestingRequestController::class, 'store']);
Route::get('slot-list', [Api\Azalea\SlotController::class, 'getSlots']);
Route::get('patient-data', [Api\Azalea\PatientController::class, 'getPatientData']);
Route::get('patient-appointment', [Api\AppointmentController::class, 'getPatientAppointment']);
Route::get('azalea-token', [Api\Azalea\AuthController::class, 'getAccessToken']);
Route::get('clinician-insurances', [Api\ClinicianInsuranceController::class, 'index']);
Route::get('insurance-plans/{insuranceId}', [Api\ClinicianInsuranceController::class, 'getPlans']);
Route::get('insurances', [Api\TruBillingInsuranceController::class, 'index']);
Route::get('insurance-plans', [Api\TruBillingInsurancePlanController::class, 'index']);
Route::post('update-appointment', [Api\AppointmentController::class, 'updateStatus']);
Route::post('reschedule-appointment', [Api\AppointmentController::class, 'rescheduleAppointment']);
Route::get('/{any?}', function () {
    return view('welcome');
});

Route::get('/{any?}/{subdomain?}', function () {
    return view('welcome');
});

Route::get('/{any?}/{subdomain?}/{id?}', function () {
    return view('welcome');
});