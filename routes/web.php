<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Landing Page & Application Submission
Route::get('/', [LandingController::class, 'index'])->name('welcome');
Route::post('/apply', [LandingController::class, 'storeApplication'])->name('client.apply');

// Authentication Routes
Route::get('/login', function (Request $request) {
    return view('auth.login', ['role' => $request->query('role', 'client')]);
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (Auth::attempt([$field => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// Dashboard Gateway Redirector based on user role
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'staff') {
        return redirect()->route('staff.dashboard');
    } else {
        return redirect()->route('client.dashboard');
    }
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [PaymentController::class, 'adminAnalytics'])->name('admin.dashboard');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Check admin role inside group
    if (auth()->check() && auth()->user()->role !== 'admin') {
        // Prevent unauthorized registration execution
    }

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/staff/store', [AdminController::class, 'storeStaff'])->name('staff.store');
    Route::post('/loans/{loan}/assign', [AdminController::class, 'assignStaff'])->name('loans.assign');
    Route::post('/loans/{loan}/status', [AdminController::class, 'updateStatus'])->name('loans.status');
    Route::post('/rates/{product}', [AdminController::class, 'updateRates'])->name('rates.update');
});


// Staff Routes
Route::middleware(['auth'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    Route::post('/loans/{loan}/ci', [StaffController::class, 'updateCI'])->name('loans.ci');
    Route::post('/payments', [StaffController::class, 'storePayment'])->name('payments.store');
    Route::post('/loans/{loan}/decline', [StaffController::class, 'declineLoan'])->name('loans.decline');
});

// Loan Action & Direct Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/loans/{loan}/action', [LoanController::class, 'action'])->name('loans.action');
    Route::post('/loans/{loan}/direct-payment', [LoanController::class, 'storeDirectPayment'])->name('loans.direct-payment');
});

// Client Routes
Route::middleware(['auth'])->prefix('client')->name('client.')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
});

// Add this route inside your web.php
Route::middleware(['auth'])->group(function () {
    // Other routes...
    
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
});