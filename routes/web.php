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
    return redirect('dashboard');
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
    $route->get('/ticket/{id}/become-agent', 'TicketController@becomeAgent')->name('ticket.agent.become');
    $route->get('/ticket/{id}/transfer-agent', 'TicketController@transferAgent')->name('ticket.agent.transfer');
    $route->get('/ticket/{id}/close', 'TicketController@close')->name('ticket.close');
    $route->get('/ticket/{id}/rate/{value?}', 'TicketController@rate')->name('ticket.rate');

    $route->get('img/ticket/{filename}', 'TicketController@getFile')->name('ticket.file.download');


    $route->post('/ticket', 'TicketController@save')->name('ticket.save');
    $route->post('/ticket/upload', 'TicketController@uploadFile')->name('ticket.upload');
    $route->post('/ticket/{id}', 'TicketController@update')->name('ticket.update');

    $route->get('/profile', function() {
    })->name('profile');

    $route->get('controller_room', 'ControllerRoomController@index' )->name('controller_room');
    $route->post('controller_room', 'ControllerRoomController@filter' )->name('controller_room');

    $route->get('notifications', 'NotificationController@index')->name('notifications');
    $route->get('notifications/list', 'NotificationController@list')->name('notifications.list');

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/password/change', 'ProfileController@change')->name('password.change');
Route::post('/password/change', 'ProfileController@save');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
