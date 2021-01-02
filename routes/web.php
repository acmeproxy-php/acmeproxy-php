<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

use Illuminate\Http\Request;

// Default Route
$router->get('/', function () use ($router) {});
// Acme Routes
$router->post('/present', "Controller@present");
$router->post('/cleanup', "Controller@cleanup");
