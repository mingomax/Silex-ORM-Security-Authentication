<?php
/**
 * Unit tests for UserTest.php
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

use Sosa\Persistence\Entities\User;

class UserTest extends PHPUnit_Framework_TestCase
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
    }

    public function testGetFake()
    {
        $user = new User();
        $user = $user->getFake($this->app);
        $this->assertTrue(0 < strlen($user->getFirstName()));
        $this->assertTrue(0 < strlen($user->getLastName()));
        $this->assertTrue(0 < strlen($user->getUsername()));
        $this->assertTrue(0 < strlen($user->displayName()));
        $this->assertTrue(0 < strlen($user->getEmail()));
        $this->assertTrue(0 < strlen($user->getPassword()));
        $this->assertTrue(0 < strlen($user->getSalt()));
        $this->assertTrue(0 < strlen($user->getCreatedAt()->getTimestamp()));
        $this->assertTrue(0 < strlen($user->getUpdatedAt()->getTimestamp()));
        $this->assertTrue(0 < count($user->getRoles()));
    }

    public function testGetTest()
    {
        $user = new User();
        $user = $user->getTest($this->app);
        $this->assertTrue(0 < strlen($user->getFirstName()));
        $this->assertTrue(0 < strlen($user->getLastName()));
        $this->assertTrue(0 < strlen($user->getUsername()));
        $this->assertTrue(0 < strlen($user->displayName()));
        $this->assertTrue(0 < strlen($user->getEmail()));
        $this->assertTrue(0 < strlen($user->getPassword()));
        $this->assertTrue(0 < strlen($user->getSalt()));
        $this->assertTrue(0 < strlen($user->getCreatedAt()->getTimestamp()));
        $this->assertTrue(0 < strlen($user->getUpdatedAt()->getTimestamp()));
        $this->assertTrue(0 < count($user->getRoles()));
    }
}

