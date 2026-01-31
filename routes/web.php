<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// مسیر اصلی
Route::get('/', [IdeaController::class, 'index'])->name('ideas.index');

// مسیرهای نیازمند ورود
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/ideas', [IdeaController::class, 'store'])->name('ideas.store');
    Route::post('/ideas/{idea}/vote', [IdeaController::class, 'vote'])->name('ideas.vote');
    Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('ideas.destroy');
    // مسیر جدید خواندن اعلان‌ها
    Route::get('/notifications/read', [IdeaController::class, 'markNotificationsAsRead'])->name('notifications.read');
});

// ریدایرکت داشبورد
Route::get('/dashboard', function () {
    return redirect()->route('ideas.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// پروفایل
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Artisan;

Route::get('/init-db', function () {
    try {
        Artisan::call('migrate:fresh --seed --force');
        return "✅ دیتابیس با موفقیت ساخته و آماده شد!";
    } catch (\Exception $e) {
        return "❌ خطا: " . $e->getMessage();
    }
});