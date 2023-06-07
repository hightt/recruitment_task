<?php
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes = [
    '/' => 'src/controllers/index.php',
    '/test' => 'src/controllers/test.php',
    '/api' => 'src/controllers/api.php',
    '/calculate' => 'src/controllers/calculate.php',
    '/history' => 'src/controllers/calculateHistory.php'
];

if(array_key_exists($uri, $routes)) {
    include $routes[$uri];
} else {
    http_response_code(404);
}

?>