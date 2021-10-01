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

// Hereunder we specify routes generally accessible
Route::get('/', function () {
    return view('welcome');
});


Route::group(["middleware" => ['auth:sanctum','verified']], function() {
// Hereunder we specify routes accesible only by administrators
    Route::group([
        "prefix" => 'admin',
        "middleware" => 'is_admin',
        "as" => 'admin.',
    ], function() {
        Route::get('/dashboard', function() {return view('admin.dashboard');})->name('dashboard');
        Route::get('/users', function () {return view('admin.user');})->name('users');
        Route::get('/members', function () {return view('admin.members');})->name('members');
        Route::get('/competition-teams', function () {return view('admin.competition-teams');})->name('competition-teams');
        Route::get('/competitions', function () {return view('admin.competitions');})->name('competitions');
        Route::get('/calendars', function () {return view('admin.calendars');})->name('calendars');
    });
// Hereunder we specify routes accessible only by members
    Route::group([
        "prefix" => 'member',
        "middleware" => 'is_member',
        "as" => 'member.',
    ], function() {
        Route::get('/dashboard', function(){return view('member.dashboard');})->name('dashboard');
        Route::get('/competitions', function () {return view('member.competitions');})->name('competitions');
    });
// Hereunder we specify routes accessible only by members
    Route::group([
        "prefix" => 'user',
        "as" => 'user.',
    ], function(){
        Route::get('/home', function() {
            return view('user.home');
        })->name('home');
    });

});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');