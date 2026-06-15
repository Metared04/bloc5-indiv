<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

session_start();

if (!isset($_SESSION['user']) && isset($_COOKIE['remember_email']) && isset($_COOKIE['remember_token'])){
    $cookieEmail = $_COOKIE['remember_email'];
    $cookieToken = $_COOKIE['remember_token'];

    $user = \App\Models\User::getByLogin($cookieEmail);

    if ($user && hash_equals(\App\Utility\Hash::generate($cookieEmail, $user['salt']), $cookieToken)){
        $_SESSION['user'] = [
            'id'       => $user['id'],
            'username' => $user['username'],
        ];
    } else {
        setcookie('remember_email', '', time() - 3600, '/');
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('login', ['controller' => 'User', 'action' => 'login']);
$router->add('register', ['controller' => 'User', 'action' => 'register']);
$router->add('logout', ['controller' => 'User', 'action' => 'logout']);
$router->add('account', ['controller' => 'User', 'action' => 'account', 'private' => true]);
$router->add('product', ['controller' => 'Product', 'action' => 'index', 'private' => true]);
$router->add('product/{id:\d+}', ['controller' => 'Product', 'action' => 'show']);
$router->add('{controller}/{action}');
$router->add('contact', ['controller' => 'Product', 'action' => 'contact']);

/*
 * Gestion des erreurs dans le routing
 */
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch(Exception $e){
    switch($e->getMessage()){
        case 'You must be logged in':
            header('Location: /login');
            break;
    }
}
