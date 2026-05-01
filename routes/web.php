<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Bloxpin
|--------------------------------------------------------------------------
*/

// ─── Public Routes ───────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggest', [SearchController::class, 'suggest'])->name('search.suggest');

// Route::get('/photo/{uid}', [PhotoController::class, 'show'])->name('photos.show');
Route::get('/photo/{photo:uid}', [PhotoController::class, 'show'])->name('photos.show');
Route::post('/photo/{photo:uid}/like', [PhotoController::class, 'like'])->name('photos.like');
// dst.
Route::get('/user/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/board/{board}', [BoardController::class, 'show'])->name('boards.show');

// ─── Authenticated Routes ────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Photo CRUD
    Route::get('/upload', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('/upload', [PhotoController::class, 'store'])->name('photos.store');
    Route::get('/photo/{photo}/edit', [PhotoController::class, 'edit'])->name('photos.edit');
    Route::put('/photo/{photo}', [PhotoController::class, 'update'])->name('photos.update');
    Route::delete('/photo/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    // Board CRUD
    Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create');
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
    Route::get('/board/{board}/edit', [BoardController::class, 'edit'])->name('boards.edit');
    Route::put('/board/{board}', [BoardController::class, 'update'])->name('boards.update');
    Route::delete('/board/{board}', [BoardController::class, 'destroy'])->name('boards.destroy');

    // Like toggle (AJAX)
    Route::post('/photo/{photo}/like', [LikeController::class, 'toggle'])->name('photos.like');

    // Pin / Save to board (AJAX)
    Route::post('/pin', [PinController::class, 'store'])->name('pins.store');
    Route::delete('/pin', [PinController::class, 'destroy'])->name('pins.destroy');

    // Comments (AJAX)
    Route::post('/photo/{photo}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Profile Settings
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Default Breeze profile destroy
    Route::delete('/settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Follow toggle (AJAX)
    Route::post('/user/{user:username}/follow', [\App\Http\Controllers\FollowController::class, 'toggle'])->name('user.follow');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');

    // ─── Admin Routes (God Mode) ──────────────────────────────────
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('users.delete');

        // Photo Management
        Route::get('/photos', [\App\Http\Controllers\Admin\AdminController::class, 'photos'])->name('photos');
        Route::delete('/photos/{photo}', [\App\Http\Controllers\Admin\AdminController::class, 'deletePhoto'])->name('photos.delete');
    });
});

require __DIR__.'/auth.php';
