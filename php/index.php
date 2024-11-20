<?php

require_once 'gameMap.php';

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

post('/move', function () {
    // 1. Определить все сущности на карте
    //  1.1 Определить положение своего корабля и направление (тут P - это наш корабль и вторая буква - направление)
    //  1.2 Определить астероиды (сделать массив с астероидами и их координатами, [ [0,0], [0,1], [0,2] ... ]
    //  1.3 Определить монеты (так же как и с астероидами)
    //  1.4 Определить врагов (тут помимо координат, нужно направление, [ [0,0, 'W'], [0,1, 'S'], [0,2, 'N'] ... ])

    $jsonData = json_decode(file_get_contents('php://input'), true);
    $field = $jsonData['field'];
    $narrowingIn = $jsonData['narrowingIn'];
    $gameId = $jsonData['gameId'];

    $gameMap = new GameMap($field);

    if ($gameMap->isFire()) {
        $action = 'F';
    } else {
        $action = $gameMap->nextMove();
    }

//    $closestCoinPath = null;
//
//    foreach ($pathsToCoins as $path) {
//        if ($closestCoinPath === null || count($path) < count($closestCoinPath)) {
//            $closestCoinPath = $path;
//        }
//    }
//
//    $nextRowDirection = null;
//    $nextColumnDirection = null;
//
//    if ($closestCoinPath !== null) {
//        $nextPointPosition = $closestCoinPath[1];
//
//        if ($nextPointPosition[0] > $playerPosition[0]) {
//            $nextRowDirection = 'S';
//        } elseif ($nextPointPosition[0] < $playerPosition[0]) {
//            $nextRowDirection = 'N';
//        }
//
//        if ($nextPointPosition[1] < $playerPosition[1]) {
//            $nextColumnDirection = 'W';
//        } elseif ($nextPointPosition[1] > $playerPosition[1]) {
//            $nextColumnDirection = 'E';
//        }
//
//        if ($nextRowDirection) {
//            if ($nextRowDirection === $playerDirection) {
//                $final_move = 'M';
//            } else {
//                $directionIndex = $playerDirection . $nextRowDirection;
//
//                $direction = $directions[$directionIndex];
//
//                $final_move = $direction;
//            }
//        }
//
//        if ($nextColumnDirection) {
//            if ($nextColumnDirection === $playerDirection) {
//                $final_move = 'M';
//            } else {
//                $directionIndex = $playerDirection . $nextColumnDirection;
//
//                $direction = $directions[$directionIndex];
//
//                $final_move = $direction;
//            }
//        }
//    }


    return [
        'move' => $action
    ];
});

dispatch();
