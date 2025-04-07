<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;


Route::middleware("auth")->group(function () {
    Route::inertia("/", "Home")->name("home");
    Route::inertia("/about", "About")->name("about");
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");


    Route::prefix("/posts")->controller(PostController::class)->group(function () {
        Route::get("/", "index")->name("posts.index");
        Route::get("/beta", "infiniteScroll")->name("posts.beta");

        Route::get("/create", "create")->name("posts.create");
        Route::post("/", "store")->name("posts.store");
    });
});

Route::middleware("guest")->group(function () {
    Route::get("/login", [AuthController::class, "loginPage"])->name("login");
    Route::post("/login", [AuthController::class, "login"]);

    Route::get("/register", [AuthController::class, "registerPage"])->name("register");
    Route::post("/register", [AuthController::class, "register"]);
});
