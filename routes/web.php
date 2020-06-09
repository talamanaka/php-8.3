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

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/',  ['uses' => 'CovidppController@getBaseData']);

    /*$router->get('curados', ['uses' => 'CovidppController@curados']);

    $router->get('notificacoes', ['uses' => 'CovidppController@notificacoes']);

    $router->get('descartados', ['uses' => 'CovidppController@descartados']);

    $router->get('aguardando', ['uses' => 'CovidppController@aguardando']);

    $router->get('hospitalizados', ['uses' => 'CovidppController@hospitalizados']);

    $router->get('confirmados', ['uses' => 'CovidppController@confirmados']);

    $router->get('obitos', ['uses' => 'CovidppController@obitos']);

    $router->get('prevalencia', ['uses' => 'CovidppController@prevalencia']);

    $router->get('descartadosPercentual', ['uses' => 'CovidppController@descartadosPercentual']);
    
    $router->get('confirmadosPercentual', ['uses' => 'CovidppController@confirmadosPercentual']);
    
    $router->get('resultadosPercentual', ['uses' => 'CovidppController@resultadosPercentual']);
    
    $router->get('incrementoPercentual', ['uses' => 'CovidppController@incrementoPercentual']);*/

});