<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// API route group
$router->group(['prefix' => 'api/v1'], function () use ($router) {

    // Matches "/api/v1/login
    $router->post('login', 'AuthController@login');

    $router->group(['middleware' => 'jwt.auth'], function () use ($router) {

        $router->group(['prefix' => 'users'], function () use ($router){
            $router->get('/with-balance', 'UserController@getUsersWithBalance');
            $router->get('/with-balance/{id}', 'UserController@getUserByIdWithBalance');
            $router->get('/', 'UserController@getUsers');
            $router->get('/{id}', 'UserController@getUserById');
            $router->post('/','UserController@createUser');
            $router->put('/{id}', 'UserController@updateUser');
            $router->delete('/{id}', 'UserController@deleteUser');
        });

        $router->group(['prefix' => 'user-balance'], function () use ($router){
            $router->get('/','UserBalanceController@getAllBalance');
            $router->get('/{id}','UserBalanceController@getBalanceById');
            $router->post('/','UserBalanceController@createBalance');
            $router->post('/transfer','UserBalanceController@transferToBank');
            $router->post('/retrieve','UserBalanceController@transferFromBank');
            $router->delete('/{id}','UserBalanceController@deleteBalance');
        });

        $router->group(['prefix' => 'bank-balance'], function () use ($router){
            $router->get('/','BalanceBankController@getAllBalance');
            $router->get('/{id}','BalanceBankController@getBalanceById');
            $router->post('/','BalanceBankController@createBalance');
            $router->delete('/{id}','BalanceBankController@deleteBalance');
        });

        $router->post('/transfer-to-bank','UserBalanceController@transferToBank');
        $router->post('/logout', 'AuthController@logout');
    });
});

$router->get('/key', function() {
    return \Illuminate\Support\Str::random(32);
});
