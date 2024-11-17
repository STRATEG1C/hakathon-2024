<?php

$routes = [];

function get(string $path, callable $callback)
{
    global $routes;
    $routes['GET'][$path] = $callback;
}

function post(string $path, callable $callback)
{
    global $routes;
    $routes['POST'][$path] = $callback;
}

function dispatch()
{
    global $routes;
    $method = $_SERVER['REQUEST_METHOD'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    header('Content-Type: application/json');

    if (isset($routes[$method][$path])) {
        echo json_encode(call_user_func($routes[$method][$path]));
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Page not found !']);
    }
}

get('/healthz', function () {
    return ['status' => 'OK'];
});

get('/', function () {
    return ['data' => 'Hello, world !'];
});

dispatch();
