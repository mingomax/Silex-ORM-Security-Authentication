<?php
/**
 * Services registering
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\HttpCacheServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use JMS\SerializerServiceProvider\SerializerServiceProvider;
use Sosa\Persistence\Tools\EditUserVoter;
use Sosa\Providers\UserProvider;

// -----------------------------------------------
// Services registering
$app->register(new ServiceControllerServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new SessionServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => $app['baseDir'].'/resources/cache',
));

$app->register(new SerializerServiceProvider(), array(
    'serializer.src_directory' => $app['baseDir'] . '/vendor/jms/serializer-bundle/src',
    'serializer.cache.directory' => $app['baseDir'] . '/resources/cache'
));

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => $app['baseDir'] . '/resources/log/development.log',
));

$app->register(new TwigServiceProvider(), array(
    'twig.path'       => __DIR__ . '/views',
    //'twig.class_path' => $app['baseDir'] . '/vendor/twig/lib',
));

$app->register(new DoctrineServiceProvider(), array(
    'db.options' => $app['db.options']));

$app->register(new DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => $app['baseDir'] . '/resources/cache',
    "orm.em.options" => array(
        "mappings" => array(
            // Using actual filesystem paths
            array(
                "type" => "annotation",
                "namespace" => "Sosa\Persistence\Entities",
                "path" => __DIR__."/../../Sosa/Persistence/Entities",
                "use_simple_annotation_reader" => false
            )
        )
    )
));

$app['user.provider'] = $app->share(function($app) {
    return new UserProvider($app['orm.em']->getConnection(), $app);
});

$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login' => array(
            'pattern' => '^/login$',
        ),
        'secured' => array(
            'pattern' => '^.*$',
            'form' => array('login_path' => '/login', 'check_path' => '/check_login'),
            'logout' => array('logout_path' => '/logout'),
            'users' => $app['user.provider']
        ),
    ),
    'security.access_rules' => array(
        array('^/login$', ''),
        array('^.*$', 'ROLE_USER'), //anonymous routes
    )
));

$app['user'] = $app->share(function($app) {
    return ($app['user.provider']->getCurrentUser());
});


