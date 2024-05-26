<?php 

require_once 'Configuration.php'; /* ovaj fajl ne pripada psr-4 autoloading namespace-u */
require_once 'vendor/autoload.php';

// TODO: Ako dodamo novi dir i namespace moramo da uradimo komandu composer dump-autoload

use App\Core\Router;


$databaseConfig = new \App\Core\DatabaseConfig(
    Configuration::DB_HOST,
    Configuration::DB_NAME,
    Configuration::DB_USER,
    Configuration::DB_PASS);

$databaseConnection = new \App\Core\DatabaseConnection($databaseConfig);



$url = strval(filter_input(INPUT_GET, "URL"));
$httpMethod = filter_input(INPUT_SERVER, "REQUEST_METHOD");

$router = new Router();
$routes = require_once 'Routes.php';
foreach ($routes as $route) {

    $router->add($route);
}

$route = $router->find($httpMethod, $url);
if($route === null){
    header("Location: " . Configuration::BASE . "404");
}
$arguments = $route->extractArguments($url);

$fullControllerName = "\\App\\Controllers\\" . $route->getControllerName() . "Controller";

$controller = new $fullControllerName($databaseConnection);


call_user_func_array([$controller, $route->getMethodName()], $arguments); 

$data = $controller->getData();

$loader = new \Twig\Loader\FilesystemLoader('./views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);


$data["BASE"] = Configuration::BASE;

echo $twig->render($route->getControllerName() . '/' . $route->getMethodName() . ".html", $data);