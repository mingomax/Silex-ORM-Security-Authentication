<?php
/**
 * PresentationsCollectionTest.php
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

class PresentationsCollectionTest  extends PHPUnit_Framework_TestCase
{

    protected $app;

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

    public function testHavePresentations()
    {
        $users = $this->app['orm.em']->getRepository('Sosa\Persistence\Entities\User')->findAll();
        $this->assertGreaterThan(1, count($users));
    }

}
