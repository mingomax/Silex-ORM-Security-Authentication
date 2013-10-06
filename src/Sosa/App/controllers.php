<?php
/**
 * Controllers
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

namespace Sosa\App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Sosa\App\Controllers\SecurityController;

// Controllers registering
$app['controllers.security'] = $app->share(function() use ($app) {
        return new SecurityController($app);
});

// login route
$app->match('/login', "controllers.security:login")->bind('login');

/**
 * Home page
 */
$app->get('/', function() use ($app) {
    return $app['twig']->render('home.html.twig', array(
        'title' => "Accueil"
    ));
})->bind('homepage');


