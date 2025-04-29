<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ConferenceSignUpPage;
use App\Livewire\ConferenceSignUp;

Route::get('/conference-sign-up', ConferenceSignUpPage::class);

Route::get('/register-attendee')
    ->name('register-attendee')
    ->uses(ConferenceSignUp::class)
    ;
    
