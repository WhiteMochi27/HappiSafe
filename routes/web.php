<?php

// use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// Authentication protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Insurance routes
    Route::prefix('insurance')->name('insurance.')->group(function () {
        Route::get('/', [InsuranceController::class, 'index'])->name('index');
        Route::get('/category/{slug}', [InsuranceController::class, 'category'])->name('category');
        Route::get('/product/{id}', [InsuranceController::class, 'show'])->name('show');
        Route::get('/checkout/{id}', [InsuranceController::class, 'checkout'])->name('checkout');
        Route::post('/purchase', [InsuranceController::class, 'purchase'])->name('purchase');
        Route::get('/renew/{id}', [InsuranceController::class, 'renew'])->name('renew');
        Route::post('/renew/{id}', [InsuranceController::class, 'processRenewal'])->name('process_renewal');
    });

    // Membership routes
    Route::prefix('membership')->name('membership.')->group(function () {
        Route::get('/', [MembershipController::class, 'plans'])->name('plans');
        Route::get('/{tier}', [MembershipController::class, 'planDetails'])->name('details');
        Route::get('/checkout/{tier}', [MembershipController::class, 'checkout'])->name('checkout');
        Route::post('/purchase', [MembershipController::class, 'purchase'])->name('purchase');
    });

    // Profile routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Vehicle routes
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::get('/add', [VehicleController::class, 'create'])->name('create');
        Route::post('/', [VehicleController::class, 'store'])->name('store');
        Route::get('/{id}', [VehicleController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [VehicleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VehicleController::class, 'update'])->name('update');
        Route::delete('/{id}', [VehicleController::class, 'destroy'])->name('destroy');
    });

    // Family Group routes
    Route::prefix('family')->name('family.')->group(function () {
        Route::get('/', [FamilyController::class, 'index'])->name('index');
        Route::get('/create', [FamilyController::class, 'create'])->name('create');
        Route::post('/', [FamilyController::class, 'store'])->name('store');
        Route::get('/{id}', [FamilyController::class, 'show'])->name('show');
        Route::get('/invite', [FamilyController::class, 'showInviteForm'])->name('invite_form');
        Route::post('/invite', [FamilyController::class, 'invite'])->name('invite');
        Route::get('/join/{token}', [FamilyController::class, 'join'])->name('join');
        Route::post('/accept/{token}', [FamilyController::class, 'accept'])->name('accept');
        Route::delete('/invitation/{id}', [FamilyController::class, 'cancelInvitation'])->name('cancel_invitation');

        // Family Members
        Route::prefix('members')->name('members.')->group(function () {
            Route::put('/{id}', [FamilyController::class, 'updateMember'])->name('update');
            Route::delete('/{id}', [FamilyController::class, 'removeMember'])->name('remove');
        });
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [DashboardController::class, 'notifications'])->name('index');
        Route::put('/{id}/read', [DashboardController::class, 'markAsRead'])->name('mark_read');
        Route::put('/read-all', [DashboardController::class, 'markAllAsRead'])->name('mark_all_read');
    });

    // Transactions
    Route::get('/transactions', [ProfileController::class, 'transactions'])->name('transactions');
});

// Contact page
Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

// About pages
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', function () {
        return Inertia::render('About');
    })->name('index');

    Route::get('/terms', function () {
        return Inertia::render('About/Terms');
    })->name('terms');

    Route::get('/privacy', function () {
        return Inertia::render('About/Privacy');
    })->name('privacy');

    Route::get('/cookie-policy', function () {
        return Inertia::render('About/CookiePolicy');
    })->name('cookie-policy');
});

// FAQ page
Route::get('/faqs', function () {
    return Inertia::render('FAQs');
})->name('faqs');

// Include other route files
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
