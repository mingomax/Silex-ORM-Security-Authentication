<?php
/**
 * Sosa Silex Doctrine-related Console Commands
 *
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

namespace Sosa\Console\Commands;

use Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Command\Command,
    Sosa\Persistence\Tools\Fixtures;

class FixturesLoad extends Command
{

   protected $container;

    /**
     * configure cli command
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('dbal:fixtures:load')
            ->setDescription('Generate random datas fixtures into db')
            ->addOption(
                'purge',
                null,
                InputOption::VALUE_NONE,
                'Si défini, suppression de toutes les tables et de leurs contenus'
            );
    }

   /**
    * setContainer
    * Shameless coupling
    *
    * @param Silex\Application $container
    * @return void
    */
   public function setContainer($container) {
       $this->container = $container;
   }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getHelper('em')->getEntityManager();
        $fixtures = new Fixtures($this->container);

        if ($input->getOption('purge')) {
            $fixtures->purge($em, $input, $output);
        }

        $fixtures->load($em);

        $output->write('Fixtures loaded.' . PHP_EOL);
    }
}
