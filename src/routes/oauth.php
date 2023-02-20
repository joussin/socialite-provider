<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::middleware('web')->get('/oauth/redirect', function () {
    return \Laravel\Socialite\Facades\Socialite::driver('mbc')->redirect();
})->name('oauth.login');

Route::middleware('web')->get('/oauth/login/callback', function () {

    $user = Laravel\Socialite\Facades\Socialite::driver('mbc')->user();

    $userLaravel = Laravel\Socialite\Facades\Socialite::driver('mbc')->mapObjectToModel($user);

    \Illuminate\Support\Facades\Auth::login($userLaravel);

    return redirect()->route('backoffice.home');

})->name('oauth.login.callback');

