<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Farmer\FarmerDashboardController;
use App\Http\Controllers\Farmer\TransportRequestController;
use App\Http\Controllers\Farmer\FarmerBookingController;
use App\Http\Controllers\Transporter\TransporterDashboardController;
use App\Http\Controllers\Transporter\TransportListingController;
use App\Http\Controllers\Transporter\TransporterBookingController;
use App\Http\Controllers\Agent\AgentDashboardController;
use App\Http\Controllers\Agent\MarketPriceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminMarketController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MarketIntelligenceController;
use App\Http\Controllers\DisputeController;
use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\LoadBoardController;
use App\Http\Controllers\FareCalculatorController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\KycController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// ── Public Load Board & Tools ────────────────────────────────
Route::get('/load-board', [LoadBoardController::class, 'index'])->name('load-board');
Route::get('/fare-calculator', [FareCalculatorController::class, 'index'])->name('fare-calculator');
Route::post('/fare-calculator', [FareCalculatorController::class, 'calculate'])->name('fare-calculator.calculate');
Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
Route::get('/about', fn() => view('pages.about'))->name('about');
Route::get('/contact', fn() => view('pages.contact'))->name('contact');
Route::get('/crop-calendar', fn() => view('crop-calendar.index'))->name('crop-calendar');
Route::get('/terms', fn() => view('pages.terms'))->name('terms');
Route::get('/privacy', fn() => view('pages.privacy'))->name('privacy');

// Onboarding dismiss
Route::post('/onboarding/dismiss', function () {
    session(['onboarding_dismissed' => true]);
    return response()->json(['ok' => true]);
})->middleware('auth');

// SEO Sitemap
Route::get('/sitemap.xml', function () {
    $urls = collect([
        ['url' => url('/'), 'priority' => '1.0'],
        ['url' => url('/about'), 'priority' => '0.8'],
        ['url' => url('/contact'), 'priority' => '0.8'],
        ['url' => url('/load-board'), 'priority' => '0.9'],
        ['url' => url('/fare-calculator'), 'priority' => '0.7'],
        ['url' => url('/leaderboard'), 'priority' => '0.7'],
        ['url' => url('/crop-calendar'), 'priority' => '0.7'],
        ['url' => url('/terms'), 'priority' => '0.3'],
        ['url' => url('/privacy'), 'priority' => '0.3'],
    ]);
    return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
});

// ── Generic dashboard redirect (role-aware) ──────────────────
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->role) {
        'farmer' => redirect()->route('farmer.dashboard'),
        'transporter' => redirect()->route('transporter.dashboard'),
        'agent' => redirect()->route('agent.dashboard'),
        'admin' => redirect()->route('admin.dashboard'),
        default => view('dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ── Profile Routes ───────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ══════════════════════════════════════════════════════════════
// FARMER ROUTES
// ══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'role:farmer'])->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [FarmerDashboardController::class, 'index'])->name('dashboard');

    // Transport Requests
    Route::resource('requests', TransportRequestController::class);
    Route::get('/requests/{request}/matches', [TransportRequestController::class, 'matches'])->name('requests.matches');

    // Bookings
    Route::get('/bookings', [FarmerBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [FarmerBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm-delivery', [FarmerBookingController::class, 'confirmDelivery'])->name('bookings.confirm-delivery');

    // Tracking
    Route::get('/bookings/{booking}/tracking', [TrackingController::class, 'show'])->name('bookings.tracking');

    // Payments
    Route::get('/wallet', [PaymentController::class, 'wallet'])->name('wallet');
    Route::post('/wallet/topup', [PaymentController::class, 'topup'])->name('wallet.topup');
    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'pay'])->name('bookings.pay');
    Route::get('/bookings/{booking}/invoice', [PaymentController::class, 'invoice'])->name('bookings.invoice');

    // Messages
    Route::get('/bookings/{booking}/messages', [MessageController::class, 'index'])->name('bookings.messages');
    Route::post('/bookings/{booking}/messages', [MessageController::class, 'store'])->name('bookings.messages.store');

    // Reviews
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('bookings.review');

    // Market Intelligence
    Route::get('/market-prices', [MarketIntelligenceController::class, 'index'])->name('market-prices');

    // Cooperatives
    Route::get('/cooperatives', [CooperativeController::class, 'index'])->name('cooperatives.index');
    Route::get('/cooperatives/create', [CooperativeController::class, 'create'])->name('cooperatives.create');
    Route::post('/cooperatives', [CooperativeController::class, 'store'])->name('cooperatives.store');
    Route::get('/cooperatives/{cooperative}', [CooperativeController::class, 'show'])->name('cooperatives.show');
    Route::post('/cooperatives/join', [CooperativeController::class, 'join'])->name('cooperatives.join');
    Route::delete('/cooperatives/{cooperative}/leave', [CooperativeController::class, 'leave'])->name('cooperatives.leave');
    Route::post('/cooperatives/{cooperative}/messages', [CooperativeController::class, 'sendMessage'])->name('cooperatives.messages.store');

    // Price Alerts
    Route::get('/price-alerts', [PriceAlertController::class, 'index'])->name('price-alerts.index');
    Route::post('/price-alerts', [PriceAlertController::class, 'store'])->name('price-alerts.store');
    Route::delete('/price-alerts/{alert}', [PriceAlertController::class, 'destroy'])->name('price-alerts.destroy');
    Route::post('/price-alerts/{alert}/toggle', [PriceAlertController::class, 'toggle'])->name('price-alerts.toggle');

    // Export
    Route::get('/export/bookings', [ExportController::class, 'bookings'])->name('export.bookings');
    Route::get('/export/requests', [ExportController::class, 'requests'])->name('export.requests');
});

// ══════════════════════════════════════════════════════════════
// TRANSPORTER ROUTES
// ══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'role:transporter'])->prefix('transporter')->name('transporter.')->group(function () {
    Route::get('/dashboard', [TransporterDashboardController::class, 'index'])->name('dashboard');

    // Transport Listings
    Route::resource('listings', TransportListingController::class);
    Route::get('/listings/{listing}/requests', [TransportListingController::class, 'nearbyRequests'])->name('listings.requests');

    // Bookings
    Route::get('/bookings', [TransporterBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TransporterBookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/accept', [TransporterBookingController::class, 'accept'])->name('bookings.accept');
    Route::post('/bookings/{booking}/pickup', [TransporterBookingController::class, 'pickup'])->name('bookings.pickup');
    Route::post('/bookings/{booking}/deliver', [TransporterBookingController::class, 'deliver'])->name('bookings.deliver');

    // Tracking Updates
    Route::post('/bookings/{booking}/tracking', [TrackingController::class, 'update'])->name('bookings.tracking.update');

    // Messages
    Route::get('/bookings/{booking}/messages', [MessageController::class, 'index'])->name('bookings.messages');
    Route::post('/bookings/{booking}/messages', [MessageController::class, 'store'])->name('bookings.messages.store');

    // Reviews
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('bookings.review');

    // Wallet
    Route::get('/wallet', [PaymentController::class, 'wallet'])->name('wallet');
    Route::post('/wallet/topup', [PaymentController::class, 'topup'])->name('wallet.topup');

    // Export
    Route::get('/export/bookings', [ExportController::class, 'bookings'])->name('export.bookings');
});

// ══════════════════════════════════════════════════════════════
// MARKET AGENT ROUTES
// ══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'role:agent'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

    // Market Prices
    Route::get('/prices', [MarketPriceController::class, 'index'])->name('prices.index');
    Route::get('/prices/create', [MarketPriceController::class, 'create'])->name('prices.create');
    Route::post('/prices', [MarketPriceController::class, 'store'])->name('prices.store');

    // Delivery Confirmation
    Route::get('/deliveries', [AgentDashboardController::class, 'deliveries'])->name('deliveries');
    Route::post('/deliveries/{booking}/confirm', [AgentDashboardController::class, 'confirmDelivery'])->name('deliveries.confirm');
});

// ══════════════════════════════════════════════════════════════
// ADMIN ROUTES
// ══════════════════════════════════════════════════════════════
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/verify', [UserManagementController::class, 'verify'])->name('users.verify');
    Route::post('/users/{user}/suspend', [UserManagementController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{user}/verify-kyc', [UserManagementController::class, 'verifyKyc'])->name('users.verify-kyc');
    Route::post('/users/{user}/reject-kyc', [UserManagementController::class, 'rejectKyc'])->name('users.reject-kyc');

    // Markets
    Route::resource('markets', AdminMarketController::class);

    // Bookings & Disputes
    Route::get('/bookings', [AdminDashboardController::class, 'bookings'])->name('bookings.index');
    Route::post('/bookings/{booking}/flag', [AdminDashboardController::class, 'flagBooking'])->name('bookings.flag');

    // Analytics
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');

    // Help Tickets (Admin management)
    Route::get('/help-tickets', [AdminDashboardController::class, 'helpTickets'])->name('help.index');
    Route::get('/help-tickets/{ticket}', [AdminDashboardController::class, 'helpTicketShow'])->name('help.show');
    Route::post('/help-tickets/{ticket}/respond', [AdminDashboardController::class, 'helpTicketRespond'])->name('help.respond');

    // Disputes (Admin management)
    Route::get('/disputes', [AdminDashboardController::class, 'disputes'])->name('disputes.index');
    Route::get('/disputes/{dispute}', [AdminDashboardController::class, 'disputeShow'])->name('disputes.show');
    Route::post('/disputes/{dispute}/resolve', [AdminDashboardController::class, 'disputeResolve'])->name('disputes.resolve');

    // Export
    Route::get('/export/bookings', [ExportController::class, 'bookings'])->name('export.bookings');
});

// ── Shared Authenticated Routes ──────────────────────────────
Route::middleware('auth')->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Help Center
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::get('/help/create', [HelpController::class, 'create'])->name('help.create');
    Route::post('/help', [HelpController::class, 'store'])->name('help.store');
    Route::get('/help/{ticket}', [HelpController::class, 'show'])->name('help.show');

    // KYC Verification
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::post('/kyc', [KycController::class, 'store'])->name('kyc.store');

    // Disputes
    Route::get('/disputes', [DisputeController::class, 'myDisputes'])->name('disputes.index');
    Route::get('/disputes/create/{booking}', [DisputeController::class, 'create'])->name('disputes.create');
    Route::post('/disputes/{booking}', [DisputeController::class, 'store'])->name('disputes.store');
    Route::get('/disputes/{dispute}', [DisputeController::class, 'show'])->name('disputes.show');

    // Bookings creation
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

    // Invoice / Receipt
    Route::get('/bookings/{booking}/invoice', function (\App\Models\Booking $booking) {
        $booking->load(['farmer', 'transporter', 'transportRequest', 'transportListing']);
        return view('bookings.invoice', compact('booking'));
    })->name('bookings.invoice');

    // Messaging Hub (direct messaging between any users)
    Route::get('/messages', [MessageController::class, 'hub'])->name('messages.hub');
    Route::get('/messages/{recipient}', [MessageController::class, 'directThread'])->name('messages.direct');
    Route::post('/messages/{recipient}', [MessageController::class, 'directSend'])->name('messages.direct.send');
});

// ── API Endpoints (for AJAX/live updates) ────────────────────
Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('/notifications/count', [NotificationController::class, 'unreadCount'])->name('notifications.count');
    Route::get('/messages/count', [MessageController::class, 'unreadCount'])->name('messages.count');
    Route::get('/bookings/{booking}/tracking', [TrackingController::class, 'latest'])->name('bookings.tracking');
    Route::get('/listings/{listing}/capacity', [TransportListingController::class, 'capacity'])->name('listings.capacity');
    Route::get('/market-prices/{crop}', [MarketIntelligenceController::class, 'priceData'])->name('market-prices.data');
});

require __DIR__.'/auth.php';

