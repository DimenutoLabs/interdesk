<?php

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

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/dashboard', 'Web\DashboardController');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');


Route::group([
    "middleware" => "auth",
], function(Router $route) {

    $route->get('/dashboard', 'DashboardController@index')->name('dashboard');
    $route->get('/ticket', 'TicketController@index')->name('ticket.home');
    $route->get('/ticket/create', 'TicketController@create')->name('ticket.create');
    $route->get('/ticket/{id}/edit', 'TicketController@edit')->name('ticket.edit');

});