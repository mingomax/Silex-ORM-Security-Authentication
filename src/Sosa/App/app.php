<?php
/**
 * Silex App set up
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

$baseDir = __DIR__ . '/../../..';
$loader = require($baseDir . '/vendor/autoload.php');
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
use Symfony\Component\HttpFoundation\Response;

// -----------------------------------------------
// App & config

$app = new Silex\Application();
$app['baseDir'] = $baseDir;
$env = isset($env) ? $env : 'dev';
if(in_array($env,array('dev','test','prod'))) {
    require $app['baseDir'] . "/resources/config/$env.php";
}

require(__DIR__ . '/services.php');
require(__DIR__ . '/controllers.php');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    $message  = "[$code] ";
    switch ($code) {
    case 404:
        $message .= "Sorry, this URL doesn't exist anymore.";
        break;
    default:
        $message .= 'Sorry, this just failed.';
        $message .= "<pre>$e . $code . </pre>";
    }

    return new Response($message, $code);
});

return $app;
