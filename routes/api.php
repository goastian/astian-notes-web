<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Note\TagController;
use App\Http\Controllers\Note\NoteController;

Route::resource('notes', NoteController::class)->except('edit', 'create');
Route::resource('tags', TagController::class)->except('edit', 'create');

