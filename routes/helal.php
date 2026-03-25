<?php

/**
 * Backend Admin Routes for Web Application
 *
 * This file contains all the routes for managing the admin panel, including routes for
 * dashboard, system Settings, profile Settings, daily tips, blogs, and natural care.
 * Routes are grouped under the 'admin' prefix and require authentication with the 'admin' middleware.
 */

use App\Http\Controllers\Web\Backend\BlogController;
use App\Http\Controllers\Web\Backend\CMS\FaqController;
use App\Http\Controllers\Web\Backend\CMS\FaqCategoryController;
use App\Http\Controllers\Web\Backend\CMS\HomePageSocialLinkContainerController;
use App\Http\Controllers\Web\Backend\ContactMessageController;
use App\Http\Controllers\Web\Backend\DynamicPageController;
use App\Http\Controllers\Web\Backend\LiveSessionRequestController;
use App\Http\Controllers\Web\Backend\PrayerRequestController;
use App\Http\Controllers\Web\Backend\ChatController;
use App\Http\Controllers\Web\Backend\NewsletterController;
use App\Http\Controllers\Web\Backend\NotificationController;
use App\Http\Controllers\Web\Backend\ProfileController;
use App\Http\Controllers\Web\Backend\SystemSettingController;
use App\Http\Controllers\Web\Backend\UserController;
use App\Http\Controllers\Web\Backend\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:web', 'role_check'])->prefix('admin')->group(function () {

    // Routes for managing guests
    Route::get('/', function () {
        return view('backend.layouts.dashboard.index');
    })->name('admin.dashboard');

    Route::get('settings-profile', [ProfileController::class, 'index'])->name('profile_settings.index');
    Route::post('settings-profile', [ProfileController::class, 'update'])->name('profile_settings.update');
    Route::get('settings-profile-password', [ProfileController::class, 'passwordChange'])->name('profile_settings.password_change');
    Route::post('settings-profile-password', [ProfileController::class, 'UpdatePassword'])->name('profile_settings.password');

    // Route for system settings index
    Route::get('system-settings', [SystemSettingController::class, 'index'])->name('system_settings.index');

    // Route for updating system settings
    Route::post('system-settings-update', [SystemSettingController::class, 'update'])->name('system_settings.update');

    // Mail Settings index
    Route::get('system-settings-mail', [SystemSettingController::class, 'mailSettingGet'])->name('system_settings.mail_get');
    // Mail Settings routes
    Route::post('system-settings-mail', [SystemSettingController::class, 'mailSettingUpdate'])->name('system_settings.mail');

    // Routes for DynamicPageController
    Route::resource('/dynamic-page', DynamicPageController::class)->names('dynamic_page');
    Route::post('/dynamic-page/status/{id}', [DynamicPageController::class, 'status'])->name('dynamic_page.status');

    // Route for UserController manage client
    Route::resource('/users', UserController::class)->names('users');
    Route::post('/users/status/{id}', [UserController::class, 'status'])->name('users.status');

    // Routes for managing app users (Clergy & Seeker)
    Route::prefix('user-management')->name('user_management.')->group(function () {
        // Clergy
        Route::get('/clergy', [UserManagementController::class, 'clergyIndex'])->name('clergy.index');
        Route::get('/clergy/create', [UserManagementController::class, 'clergyCreate'])->name('clergy.create');
        Route::post('/clergy', [UserManagementController::class, 'clergyStore'])->name('clergy.store');
        Route::get('/clergy/{id}', [UserManagementController::class, 'clergyShow'])->name('clergy.show');
        Route::get('/clergy/{id}/edit', [UserManagementController::class, 'clergyEdit'])->name('clergy.edit');
        Route::put('/clergy/{id}', [UserManagementController::class, 'clergyUpdate'])->name('clergy.update');
        Route::delete('/clergy/{id}', [UserManagementController::class, 'clergyDestroy'])->name('clergy.destroy');
        Route::post('/clergy/status/{id}', [UserManagementController::class, 'clergyStatus'])->name('clergy.status');

        // Seeker
        Route::get('/seeker', [UserManagementController::class, 'seekerIndex'])->name('seeker.index');
        Route::get('/seeker/create', [UserManagementController::class, 'seekerCreate'])->name('seeker.create');
        Route::post('/seeker', [UserManagementController::class, 'seekerStore'])->name('seeker.store');
        Route::get('/seeker/{id}', [UserManagementController::class, 'seekerShow'])->name('seeker.show');
        Route::get('/seeker/{id}/edit', [UserManagementController::class, 'seekerEdit'])->name('seeker.edit');
        Route::put('/seeker/{id}', [UserManagementController::class, 'seekerUpdate'])->name('seeker.update');
        Route::delete('/seeker/{id}', [UserManagementController::class, 'seekerDestroy'])->name('seeker.destroy');
        Route::post('/seeker/status/{id}', [UserManagementController::class, 'seekerStatus'])->name('seeker.status');
    });

    // Route for FaqController
    Route::resource('/faqs', FaqController::class)->names('faqs');
    Route::post('/faqs/status/{id}', [FaqController::class, 'status'])->name('faqs.status');

    // Route for FaqCategoryController
    Route::resource('/faq-categories', FaqCategoryController::class)->names('faq_categories');
    Route::post('/faq-categories/status/{id}', [FaqCategoryController::class, 'status'])->name('faq_categories.status');

    // Route Social link
    Route::resource('/social-link/index', HomePageSocialLinkContainerController::class)->names('social_link')->except('show');
    Route::post('/social-link/status/{id}', [HomePageSocialLinkContainerController::class, 'status'])->name('social_link.status');

    Route::resource('/blogs', BlogController::class)->names('blogs');
    Route::post('/blogs/status/{id}', [BlogController::class, 'status'])->name('blogs.status');
    Route::post('/blogs/update-container', [BlogController::class, 'updateContainer'])->name('blogs.update_container');


    // Routes for NotificationController
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notification.read');
    Route::post('/notifications/mark-as-read/single/{id}', [NotificationController::class, 'markAsSingleRead'])->name('notification.read_single');
    Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notification.delete');
    Route::delete('/notifications', [NotificationController::class, 'deleteAll'])->name('notification.deleteall');

    Route::get('/contact-us-message', [ContactMessageController::class, 'index'])->name('admin_contact_us.index');
    Route::get('/contact-us-message/{id}', [ContactMessageController::class, 'show'])->name('admin_contact_us.show');
    Route::delete('/contact-us-message/{id}', [ContactMessageController::class, 'destroy'])->name('admin_contact_us.destroy');

    // Prayer requests (admin)
    Route::get('/prayer-requests', [PrayerRequestController::class, 'index'])->name('admin.prayer_requests.index');
    Route::get('/prayer-requests/{id}', [PrayerRequestController::class, 'show'])->name('admin.prayer_requests.show');
    Route::post('/prayer-requests/{id}/assign-clergy', [PrayerRequestController::class, 'assignClergy'])->name('admin.prayer_requests.assign_clergy');
    Route::delete('/prayer-requests/{id}', [PrayerRequestController::class, 'destroy'])->name('admin.prayer_requests.destroy');

    // Live session requests (admin)
    Route::get('/live-session-requests', [LiveSessionRequestController::class, 'index'])->name('admin.live_session_requests.index');
    Route::get('/live-session-requests/{id}', [LiveSessionRequestController::class, 'show'])->name('admin.live_session_requests.show');
    Route::post('/live-session-requests/{id}/assign-clergy', [LiveSessionRequestController::class, 'assignClergy'])->name('admin.live_session_requests.assign_clergy');
    Route::post('/live-session-requests/{id}/mark-completed', [LiveSessionRequestController::class, 'markCompleted'])->name('admin.live_session_requests.mark_completed');
    Route::delete('/live-session-requests/{id}', [LiveSessionRequestController::class, 'destroy'])->name('admin.live_session_requests.destroy');

    // Chats (in-app chat rooms)
    Route::get('/chats', [ChatController::class, 'index'])->name('admin.chats.index');
    Route::get('/chats/{room}', [ChatController::class, 'show'])->name('admin.chats.show');

    // Newsletter subscriptions
    Route::get('/newsletters', [NewsletterController::class, 'index'])->name('admin.newsletters.index');
});

// Public route for dynamic pages accessible to all users
Route::get('/pages/{slug}', [DynamicPageController::class, 'showDaynamicPage'])->name('pages');

require __DIR__.'/cms.php';
