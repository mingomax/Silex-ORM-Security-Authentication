<?php
/**
 * Sosa Silex Controllers: Security-related routes
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

namespace Sosa\App\Controllers;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

Class SecurityController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * login route
     *
     * @return string The rendered template
     */
    public function login(Request $request)
    {
        $form = $this->app['form.factory']->createNamedBuilder(null, 'form',
            array('_username' => '', '_password' => ''))
            ->add('_username', 'text', array(
                'label' => 'Email',
                'attr' => array(
                    'name' => '_username',
                    'placeholder' => 'test@test.com'
                ),
                'constraints' => new Assert\Email()
            ))
            ->add('_password', 'password', array(
                'label' => 'Password',
                'attr' => array(
                    'name' => '_password',
                    'placeholder' => 'test'
                ),
                'constraints' => array(new Assert\NotBlank()
            )))
            ->getForm();

        return $this->app['twig']->render('login.html.twig', array(
            'form' => $form->createView(),
            'title' => 'Form',
            'error' => $this->app['security.last_error']($request),
            'last_username' => $this->app['session']->get('_security.last_username'),
            'allowRememberMe' => isset($this->app['security.remember_me.response_listener']),
        ));
    }
}
?>

