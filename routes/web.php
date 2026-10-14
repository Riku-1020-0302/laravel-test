<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// トップは顧客一覧へ（未ログインならauthミドルウェアでログイン画面へ）
Route::get('/', fn () => redirect()->route('customers.index'));

Route::middleware('auth')->group(function () {
    // Breezeはログイン後に route('dashboard') へ遷移するため、名前は残して顧客一覧へ転送
    Route::get('/dashboard', fn () => redirect()->route('customers.index'))->name('dashboard');

    // 顧客 CRUD（index / create / store / show / edit / update / destroy）
    Route::resource('customers', CustomerController::class);

    // タスク：追加は顧客配下、編集・更新・削除はタスク単体
    Route::post('customers/{customer}/tasks', [TaskController::class, 'store'])
        ->name('customers.tasks.store');
    Route::resource('tasks', TaskController::class)->only(['edit', 'update', 'destroy']);

    // Breeze標準のプロフィール
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';