<?php

require_once "vendor/autoload.php";


use App\Redirect;
use App\Template;
use App\ViewVariables\ErrorVariables;
use App\ViewVariables\LoginVariables;
use App\ViewVariables\PopupVariables;
use DI\DependencyException;
use DI\NotFoundException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new DI\Container();

$container->set(
    App\Repositories\CoinsRepository::class,
    DI\create(App\Repositories\CoinsFromApiRepository::class)
);
$container->set(
    App\Repositories\UserCoinRepository::class,
    DI\create(App\Repositories\UserCoinFromMysqlRepository::class)
);
$container->set(
    App\Repositories\UsersRepository::class,
    DI\create(App\Repositories\UserFromMysqlRepository::class)
);


$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['\App\Controllers\CoinController', 'index']);
    $r->addRoute('GET', '/wallet', ['\App\Controllers\MyWalletController', 'index']);
    $r->addRoute('POST', '/wallet/sell={id}', ['\App\Controllers\MyWalletController', 'sell']);
    $r->addRoute('POST', '/wallet/coin={id}/sellshort', ['\App\Controllers\MyWalletController', 'sellShort']);
    $r->addRoute('POST', '/wallet/close={id}', ['\App\Controllers\MyWalletController', 'closeShort']);
    $r->addRoute('POST', '/wallet', ['\App\Controllers\MyWalletController', 'deposit']);
    $r->addRoute('GET', '/wallet/coin={id}', ['\App\Controllers\MyWalletController', 'index']);
    $r->addRoute('POST', '/wallet/send={id}', ['\App\Controllers\MyWalletController', 'send']);
    $r->addRoute('GET', '/transactions', ['\App\Controllers\TransactionsController', 'index']);
    $r->addRoute('GET', '/coin={id}', ['\App\Controllers\CoinController', 'index']);
    $r->addRoute('POST', '/coin={id}/buy', ['\App\Controllers\CoinController', 'buy']);
    $r->addRoute('POST', '/coin={id}/sell', ['\App\Controllers\CoinController', 'sell']);
    $r->addRoute('GET', '/login', ['\App\Controllers\UserLoginController', 'index']);
    $r->addRoute('POST', '/login', ['\App\Controllers\UserLoginController', 'loginHandler']);
    $r->addRoute('GET', '/registration', ['\App\Controllers\RegistrationController', 'index']);
    $r->addRoute('POST', '/registration', ['\App\Controllers\RegistrationController', 'registrationHandler']);
    $r->addRoute('GET', '/logout', ['\App\Controllers\UserLoginController', 'logoutHandler']);
    $r->addRoute('GET', '/profile', ['\App\Controllers\ProfileController', 'index']);
    $r->addRoute('POST', '/profile', ['\App\Controllers\ProfileController', 'updateData']);


});
$loader = new FilesystemLoader('views/');
$twig = new Environment($loader, []);

$localVariables = [
    LoginVariables::class,
    ErrorVariables::class,
    PopupVariables::class
];

foreach ($localVariables as $variable) {
    try {
        $variable = $container->get($variable);
    } catch (DependencyException|NotFoundException $e) {
        echo $e->getMessage();
    }
    $twig->addGlobal($variable->getName(), $variable->getValues());
}

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];
// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        [$controller, $method] = $handler;
        try {
            $response = $container->get($controller)->{$method}($vars);
        } catch (DependencyException|NotFoundException $e) {
            echo $e->getMessage();
            $response=null;
        }

        if ($response instanceof Template) {
            try {
                echo $twig->render($response->getLink(), $response->getProperties());
                unset($_SESSION['error']);
                unset($_SESSION['popup']);
            } catch (LoaderError|RuntimeError|SyntaxError $e) {
                echo($e->getMessage());
            }
        }
        if ($response instanceof Redirect) {
            header('Location: ' . $response->getLink());
        }
        break;
}