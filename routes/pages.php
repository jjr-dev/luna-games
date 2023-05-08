<?php
    use \App\Controllers\Pages;

    $router->get('/', [
        'cache' => 100000,
        function($request, $response) {
            return Pages\Home::getPage($request, $response);
        }
    ]);

    $router->get('/game/{id}/{slug?}', [
        // 'cache' => 100000,
        function($request, $response) {
            return Pages\Game::getPage($request, $response);
        }
    ]);

    $router->get('/search', [
        'cache' => 100000,
        function($request, $response) {
            return Pages\Search::getPage($request, $response);
        }
    ]);