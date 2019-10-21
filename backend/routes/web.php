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

    $router->get('/', function () use ($router) {
        $status = 200;
        $message = config('app.name').' based on '.$router->app->version();

        $payload = [
            'status' => $status,
            'message' => $message,
            'data' => []
        ];

        return response($payload)->setStatusCode($status, $message);
    });


    $router->group([
        'prefix' => 'api',
        'namespace' => 'Api',
        'middleware' => ['Cors']
    ], function () {
        Route::options('{any}');
        //Route::get('factories', ['uses' => 'FactoryController@index']);
    });


