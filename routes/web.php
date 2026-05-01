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
use App\Http\Controllers\MessageController;
use App\Http\Controllers\CollectionController;
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

Route::get('/photo/{photo:uid}', [PhotoController::class, 'show'])->name('photos.show');
Route::get('/user/{user:username}', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/board/{board}', [BoardController::class, 'show'])->name('boards.show');
Route::get('/photo/{photo:uid}/download', [PhotoController::class, 'download'])->name('photos.download');
Route::get('/photo/{photo:uid}/embed', [PhotoController::class, 'embed'])->name('photos.embed');

// ─── Authenticated Routes ────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Photo CRUD
    Route::get('/upload', [PhotoController::class, 'create'])->name('photos.create');
    Route::post('/upload', [PhotoController::class, 'store'])->name('photos.store');
    Route::get('/photo/{photo:uid}/edit', [PhotoController::class, 'edit'])->name('photos.edit');
    Route::put('/photo/{photo:uid}', [PhotoController::class, 'update'])->name('photos.update');
    Route::delete('/photo/{photo:uid}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    // Board CRUD
    Route::get('/boards/create', [BoardController::class, 'create'])->name('boards.create');
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
    Route::get('/board/{board}', [BoardController::class, 'show'])->name('boards.show');
    Route::get('/board/{board}/edit', [BoardController::class, 'edit'])->name('boards.edit');
    Route::put('/board/{board}', [BoardController::class, 'update'])->name('boards.update');
    Route::delete('/board/{board}', [BoardController::class, 'destroy'])->name('boards.destroy');

    // Like toggle (AJAX)
    Route::post('/photo/{photo:uid}/like', [LikeController::class, 'toggle'])->name('photos.like');

    // Pin / Save to board (AJAX)
    Route::post('/pin', [PinController::class, 'store'])->name('pins.store');
    Route::delete('/pin', [PinController::class, 'destroy'])->name('pins.destroy');

    // Comments (AJAX)
    Route::post('/photo/{photo:uid}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Reports
    Route::post('/photo/{photo:uid}/report', [\App\Http\Controllers\ReportController::class, 'store'])->name('photos.report');

    // Collections
    Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
    Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
    Route::post('/collections/{collection}/toggle-photo', [CollectionController::class, 'togglePhoto'])->name('collections.toggle');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user:username}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user:username}', [MessageController::class, 'store'])->name('messages.store');

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
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
            
            // User Management
            Route::get('/users', [\App\Http\Controllers\Admin\AdminController::class, 'users'])->name('users');
            Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
            Route::post('/users/{user}/toggle-verified', [\App\Http\Controllers\Admin\AdminController::class, 'toggleVerified'])->name('users.toggle-verified');
            Route::post('/users/{user}/toggle-shadowban', [\App\Http\Controllers\Admin\AdminController::class, 'toggleShadowban'])->name('users.toggle-shadowban');
            Route::post('/users/{user}/impersonate', [\App\Http\Controllers\Admin\AdminController::class, 'impersonate'])->name('users.impersonate');
            Route::post('/users/{user}/reset-password', [\App\Http\Controllers\Admin\AdminController::class, 'resetPassword'])->name('users.reset-password');
            Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteUser'])->name('users.delete');

            // Photo Management
            Route::get('/photos', [\App\Http\Controllers\Admin\AdminController::class, 'photos'])->name('photos');
            Route::delete('/photos/{photo}', [\App\Http\Controllers\Admin\AdminController::class, 'deletePhoto'])->name('photos.delete');

            // Global Announcement
            Route::get('/announcement', [\App\Http\Controllers\Admin\AdminController::class, 'announcement'])->name('announcement');
            Route::post('/announcement', [\App\Http\Controllers\Admin\AdminController::class, 'sendAnnounce'])->name('announce.send');
            Route::delete('/announcement/{announcement}', [\App\Http\Controllers\Admin\AdminController::class, 'deleteAnnounce'])->name('announce.delete');
            
            // SQL Terminal
            Route::get('/sql', [\App\Http\Controllers\Admin\SqlController::class, 'index'])->name('sql.index');
            Route::post('/sql/execute', [\App\Http\Controllers\Admin\SqlController::class, 'execute'])->name('sql.execute');

            // Reports Management
            Route::get('/reports', [\App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('reports');
            Route::post('/reports/{report}/resolve', [\App\Http\Controllers\Admin\AdminController::class, 'resolveReport'])->name('reports.resolve');
        });
    });
});

// ─── Testing Routes (Delete later) ───────────────────────────
// Route::get('/test-error/{code}', function ($code) {
//     abort($code);
// });

require __DIR__.'/auth.php';
