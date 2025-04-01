<?php

use Illuminate\Support\Facades\Route;

Route::inertia("/","Home");
Route::inertia("/about","About");
Route::inertia("/login", "Auth/Login");
Route::inertia("/register", "Auth/Register");
