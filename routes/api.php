<?php

use App\Http\Controllers\Note\NoteController;
use Illuminate\Support\Facades\Route;

Route::resource('notes', NoteController::class)->except('edit', 'create');
