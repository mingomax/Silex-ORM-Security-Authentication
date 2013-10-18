<?php
/**
 * Unit tests for UserProviderTest.php
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

use Sosa\Providers\UserProvider;

class UserProviderTest extends PHPUnit_Framework_TestCase
{

    protected $app;
    protected $loginTest;

    public function createApplication()
    {
        // Silex
        $app = require __DIR__.'/../../src/Sosa/App/app.php';

        // Tests mode
        $app['debug'] = true;
        $app['exception_handler']->disable();

        return $app;
    }

    public function setUp()
    {
        $this->app = $this->createApplication();
        //$this->app->boot(); // See http://silex.sensiolabs.org/doc/providers/security.html
        $this->loginTest = $this->app['tests.user']['email'];
        $this->passwordTest = $this->app['tests.user']['password'];
    }

    public function testUserProviderLoaders()
    {
        $provider = new UserProvider($this->app['orm.em']->getConnection(), $this->app);
        $user = $provider->loadUserByUsername($this->loginTest);
        $this->assertTrue('Sosa\Persistence\Entities\User' === get_class($user));

        $refreshedUser = $provider->refreshUser($user);
        $this->assertTrue('Sosa\Persistence\Entities\User' === get_class($refreshedUser));
    }

    public function testUserProviderPasswordEncoder()
    {
        $provider = new UserProvider($this->app['orm.em']->getConnection(), $this->app);
        $user = $provider->loadUserByUsername($this->loginTest);
        $encodedPassword = $provider->encodeUserPassword($user, $this->passwordTest);
        $this->assertTrue($user->getPassword() === $encodedPassword);
        $this->assertTrue($user->getPassword() === $encodedPassword);
        $user2 = $provider->setUserPassword($user, $this->passwordTest);
        $this->assertTrue($user2->getPassword() === $encodedPassword);
        $this->assertTrue($provider->checkUserPassword($user, $this->passwordTest));
    }
}

