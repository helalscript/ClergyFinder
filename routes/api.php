<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\LogoutController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ResetPasswordController;
use App\Http\Controllers\API\Auth\SocialLoginController;
use App\Http\Controllers\API\Auth\UserController;
use App\Http\Controllers\API\V1\CMS\HomePageController;
use App\Http\Controllers\API\V1\CMS\HowItWorkPageController as APIHowItWorkPageController;
use App\Http\Controllers\API\V1\CMS\FindClergyPageController as APIFindClergyPageController;
use App\Http\Controllers\API\V1\CMS\OnboardingController;
use App\Http\Controllers\API\V1\QuestionController;
use App\Http\Controllers\API\V1\DashboardController;
use App\Http\Controllers\API\V1\User\NotificationController;
use App\Http\Controllers\API\V1\User\UserContactSupportController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest:api'], function ($router) {
    // register
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('/verify-email', [RegisterController::class, 'VerifyEmail']);
    Route::post('/resend-otp', [RegisterController::class, 'ResendOtp']);
    // login
    Route::post('login', [LoginController::class, 'login']);
    // forgot password
    Route::post('/forget-password', [ResetPasswordController::class, 'forgotPassword']);
    Route::post('/verify-otp', [ResetPasswordController::class, 'VerifyOTP']);
    Route::post('/reset-password', [ResetPasswordController::class, 'ResetPassword']);
    // social login
    Route::post('/social-login', [SocialLoginController::class, 'SocialLogin']);
});

Route::group(['middleware' => 'auth:api'], function ($router) {
    Route::get('/refresh-token', [LoginController::class, 'refreshToken']);
    Route::post('/logout', [LogoutController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/update-profile', [UserController::class, 'updateProfile']);
    Route::post('/update-password', [UserController::class, 'changePassword']);
});

// only for user
Route::group(['middleware' => ['auth:api', 'check_is_user']], function ($router) {
    // route for dashboard data
    Route::get('/dashboard-data/home', [DashboardController::class, 'dashboardData']);
    Route::get('/dashboard-data/history', [DashboardController::class, 'HistoryPageData']);
    Route::get('/dashboard-data/loan-management-tool', [DashboardController::class, 'LoanManagementToolPageData']);
    

    // Notifications route
    Route::get('/my-notifications', [NotificationController::class, 'index']);
    Route::post('/my-notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
    Route::delete('/my-notifications-delete/{id}', [NotificationController::class, 'delete']);
    Route::delete('/my-notifications-delete-all', [NotificationController::class, 'deleteAll']);
});

// witout auth
Route::post('/contact-support-message/sent', [UserContactSupportController::class, 'store']);


// --------- cms part --------------
Route::get('/cms/social-link', [HomePageController::class, 'getSocialLinks']);
Route::get('/cms/system-info', [HomePageController::class, 'getSystemInfo']);
Route::get('/cms/onboarding-screens', [OnboardingController::class, 'getOnboardingScreens']);

// Question Page
Route::get('/questions/{type}', [QuestionController::class, 'getQuestions'])->where('type', 'clergy|seeker');
Route::post('/questions/user-answer', [QuestionController::class, 'saveUserAnswer']);

// dynamic page
Route::get('dynamic-pages', [HomePageController::class, 'getDynamicPages']);
Route::get('dynamic-pages/single/{slug}', [HomePageController::class, 'showDaynamicPage']);

// route for home page
Route::group(['prefix' => 'cms/home-page'], function () {
    Route::get('/hero-section', [HomePageController::class, 'getHeroSection']);
    Route::get('/counter-section', [HomePageController::class, 'getCounterSection']);
    Route::get('/meet-our-clergy-section', [HomePageController::class, 'getMeetOurClergySection']);
    Route::get('/how-it-works-section', [HomePageController::class, 'getHowItWorksSection']);
    Route::get('/review-section', [HomePageController::class, 'getReviewSection']);
    Route::get('/spiritual-journey-section', [HomePageController::class, 'getSpiritualJourneySection']);
});

// route for find clergy page
Route::group(['prefix' => 'cms/find-clergy-page'], function () {
    Route::get('/hero-section', [APIFindClergyPageController::class, 'getHeroSection']);
});
 
// route for how it work page
Route::group(['prefix' => 'cms/how-it-work-page'], function () {
    Route::get('/hero-section', [APIHowItWorkPageController::class, 'getHeroSection']);
    Route::get('/work-progress-section', [APIHowItWorkPageController::class, 'getWorkProgressSection']);
    Route::get('/our-values-section', [APIHowItWorkPageController::class, 'getOurValuesSection']);
    Route::get('/spiritual-journey-section', [APIHowItWorkPageController::class, 'getSpiritualJourneySection']);
});



