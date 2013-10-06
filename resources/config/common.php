<?php

// Local
$app['locale'] = 'fr';
$app['session.default_locale'] = $app['locale'];
$app['db.options'] = array(
    'driver'    => 'pdo_mysql',
    'host'      => 'localhost',
    'dbname'    => 'sosa',
    'user'      => 'root',
    'password'  => '',
    'charset'   => 'utf8'
);

$app['tests.user'] = array(
    'first_name' => 'Test',
    'last_name' => 'TEST',
    'email'=>'test@test.com',
    'roles' => 'ROLE_USER',
    'password' => 'test',
    'created_at' => new \Datetime(),
);


