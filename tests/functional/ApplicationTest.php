<?php
/**
 * Functional tests - PresentationsCollectionTest.php
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

use Silex\WebTestCase;
use Silex\Application;

class ApplicationTest extends WebTestCase
{
    public function createApplication()
    {
        // Silex
        $env = "dev";
        $app = require __DIR__.'/../../src/Sosa/App/app.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }

    public function testIndex()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function test404()
    {
        $client = $this->createClient();

        $client->request('GET', '/give-me-a-404');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testLogin()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Email', $client->getResponse()->getContent());
        $this->assertContains('Password', $client->getResponse()->getContent());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('form[action][method="post"]')->count());
        $this->assertEquals(1, $crawler->filter('input#_username[type="text"]')->count());
        $this->assertEquals(1, $crawler->filter('input#_password[type="password"]')->count());
        $this->assertEquals(1, $crawler->filter('input#_token[type=hidden]')->count());
        $this->assertEquals(1, $crawler->filter('input[type="submit"]')->count());
    }

    public function testLoginPostFail()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('input[type="submit"]')->count());
        $form = $crawler->filter('input[type="submit"]')->form();
        $crawler = $client->submit($form, array(
            '_username' => "'&é(§!çà123467qsdfghjDFGHJKxcvbnVBN?",
            '_password' => "$^->ù=:;,*¨£%+/.?°098765"
        ));
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertTrue(is_null($this->app['security']->getToken()));
        $this->assertContains('Email', $client->getResponse()->getContent());
        $this->assertContains('Password', $client->getResponse()->getContent());
        $this->assertEquals(1, $crawler->filter('h1')->count());
        $this->assertEquals(1, $crawler->filter('form[action][method="post"]')->count());
        $this->assertEquals(1, $crawler->filter('input#_username[type="text"]')->count());
        $this->assertEquals(1, $crawler->filter('input#_password[type="password"]')->count());
        $this->assertEquals(1, $crawler->filter('input#_token[type=hidden]')->count());
        $this->assertEquals(1, $crawler->filter('input[type="submit"]')->count());
    }

    public function testLoginLogoutSuccess()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('input[type="submit"]')->count());
        $form = $crawler->filter('input[type="submit"]')->form();
        $crawler = $client->submit($form, array(
            '_username' => $this->app['tests.user']['email'],
            '_password' => $this->app['tests.user']['password']
        ));
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertTrue($this->app['security']->isGranted('IS_AUTHENTICATED_REMEMBERED'));
        $this->assertContains('Log out', $client->getResponse()->getContent());
        $link = $crawler->filter('a[href="/logout"]')->eq(0)->link();
        $crawler = $client->click($link);
        $this->assertTrue($client->getResponse()->isRedirect());

    }
}
