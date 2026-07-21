<?php

use App\Http\Controllers\todos_controller;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::get('todos', [todos_controller::class, 'index'])->name('todos.index');
    Route::get('todos/create', [todos_controller::class, 'create'])->name('todos.create');
    Route::post('todos', [todos_controller::class, 'store'])->name('todos.store');
    Route::get('todos/{todo}', [todos_controller::class, 'show'])->name('todos.show');
    Route::get('todos/{todo}/edit', [todos_controller::class, 'edit'])->name('todos.edit');
    Route::put('todos/{todo}', [todos_controller::class, 'update'])->name('todos.update');
    Route::delete('todos/{todo}', [todos_controller::class, 'destroy'])->name('todos.destroy');
    Route::patch('todos/{todo}/toggle', [todos_controller::class, 'toggleComplete'])->name('todos.toggle');
});

require __DIR__.'/settings.php';
