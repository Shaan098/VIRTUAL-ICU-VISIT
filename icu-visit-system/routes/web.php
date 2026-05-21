<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitRequestController;
use Illuminate\Support\Facades\Route;

// ── Public Pages ──────────────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');

// ── Auth redirect after login ─────────────────────────────────────────────────
Route::get('/dashboard', function () {
    $role = auth()->user()->role;
    return match($role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'doctor' => redirect()->route('doctor.dashboard'),
        'family' => redirect()->route('family.dashboard'),
        default  => redirect()->route('home'),
    };
})->middleware(['auth'])->name('dashboard');

// ── Admin Routes ──────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users',     [AdminController::class, 'users'])->name('users');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
});

// ── Doctor Routes ─────────────────────────────────────────────────────────────
Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');
});

// ── Family Routes ─────────────────────────────────────────────────────────────
Route::prefix('family')->name('family.')->middleware(['auth', 'role:family'])->group(function () {
    Route::get('/dashboard', [FamilyController::class, 'dashboard'])->name('dashboard');
});

// ── Patients (Admin + Doctor) ─────────────────────────────────────────────────
Route::resource('patients', PatientController::class)
    ->middleware(['auth', 'role:admin,doctor']);

// ── Visit Requests ────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/visit-requests',           [VisitRequestController::class, 'index'])->name('visit-requests.index');
    Route::get('/visit-requests/create',    [VisitRequestController::class, 'create'])->name('visit-requests.create')->middleware('role:family');
    Route::post('/visit-requests',          [VisitRequestController::class, 'store'])->name('visit-requests.store')->middleware('role:family');
    Route::get('/visit-requests/{visitRequest}', [VisitRequestController::class, 'show'])->name('visit-requests.show');
    Route::post('/visit-requests/{visitRequest}/approve', [VisitRequestController::class, 'approve'])->name('visit-requests.approve')->middleware('role:admin,doctor');
    Route::post('/visit-requests/{visitRequest}/reject',  [VisitRequestController::class, 'reject'])->name('visit-requests.reject')->middleware('role:admin,doctor');
    Route::delete('/visit-requests/{visitRequest}', [VisitRequestController::class, 'destroy'])->name('visit-requests.destroy');
});

// ── Meetings ──────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/meetings',               [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/{meeting}/room',[MeetingController::class, 'room'])->name('meetings.room');
    Route::post('/meetings/{meeting}/end', [MeetingController::class, 'end'])->name('meetings.end')->middleware('role:admin,doctor');
});

// ── Notifications ─────────────────────────────────────────────────────────────
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/',               [NotificationController::class, 'index'])->name('index');
    Route::get('/fetch-unread',   [NotificationController::class, 'fetchUnread'])->name('fetch');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
    Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('read');
    Route::delete('/{notification}',    [NotificationController::class, 'destroy'])->name('destroy');
});

// ── Profile ───────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
