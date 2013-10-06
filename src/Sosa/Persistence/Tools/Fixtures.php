<?php
/**
 * Sosa Silex Persistence Fixtures
 * @package Sosa
 * @author Ronan Guilloux <ronan.guilloux@gmail.com>
 * @version 0.1
 * @copyright (C) 2013 Ronan Guilloux
 * @license MIT
 */

namespace Sosa\Persistence\Tools;

use Doctrine\Common\Persistence\ObjectManager,
    Doctrine\Common\DataFixtures\FixtureInterface,
    Doctrine\ORM\Tools\SchemaTool,
    Doctrine\DBAL\Tools\Console\Command\ImportCommand,
    Sosa\Persistence\Entities\User,
    Faker\Factory;

/**
 * Fixtures
 *
 * Usage:
 *  $ ./console dba:fixtures:load
 *
 */
class Fixtures implements FixtureInterface
{

    protected $manager;
    protected $container;

    /**
     * __construct
     *
     * @param App $container
     * @return void
     */
    public function __construct($container) {
        $this->container = $container;
    }

    /**
     * purge database
     *
     * @param  ObjectManager $manager
     * @return void
     */
    public function purge(ObjectManager $manager, $input, $output)
    {
        $schemaTool = new SchemaTool($manager);
        $metadatas = $manager->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadatas);
        $schemaTool->createSchema($metadatas);

        // Adding mandatory data tables using *.sql files found in ./resources/db
        $this->addDBAssets($manager, __DIR__ . '/../../../../resources/sql/*.sql', $input, $output);
    }

    public function addDBAssets($manager, $path, $input, $output)
    {
        $fileNames = glob($path);
        foreach ((array) $fileNames as $fileName) {
            $fileName = realpath($fileName);

            if ( ! file_exists($fileName)) {
                throw new \InvalidArgumentException(
                    sprintf("SQL file '<info>%s</info>' does not exist.", $fileName)
                );
            } else if ( ! is_readable($fileName)) {
                throw new \InvalidArgumentException(
                    sprintf("SQL file '<info>%s</info>' does not have read permissions.", $fileName)
                );
            }

            $output->write(sprintf("Prooocessing file '<info>%s</info>'... ", $fileName));
            echo "here";
            $sql = file_get_contents($fileName);
            echo $sql;

            $conn = $manager->getConnection();

            if ($conn instanceof \Doctrine\DBAL\Driver\PDOConnection) {
                // PDO Drivers
                try {
                    $lines = 0;

                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    do {
                        // Required due to "MySQL has gone away!" issue
                        $stmt->fetch();
                        $stmt->closeCursor();

                        $lines++;
                    } while ($stmt->nextRowset());

                    $output->write(sprintf('%d statements executed!', $lines) . PHP_EOL);
                } catch (\PDOException $e) {
                    $output->write('error!' . PHP_EOL);

                    throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
                }
            } else {

                    $stmt = $conn->prepare($sql);
                    $rs = $stmt->execute();

                    if ($rs) {
                        $output->writeln('OK!' . PHP_EOL);
                    } else {
                        $error = $stmt->errorInfo();

                        $output->write('error!' . PHP_EOL);

                        throw new \RuntimeException($error[2], $error[0]);
                    }

                    $stmt->closeCursor();
            }
        }
    }

    /**
     * load fixtures: Users
     *
     * @param ObjectManager $manager
     * @return void
         */
        public function load(ObjectManager $manager)
        {
            // User
            $authors = array();

            $testUser = new User();
            $testUser->setFirstName($this->container['tests.user']['first_name']);
            $testUser->setLastName($this->container['tests.user']['last_name']);
            $testUser->setEmail($this->container['tests.user']['email']);
            $testUser->setRoles($this->container['tests.user']['roles']);
            $testUser->setSalt($testUser->getEmail());
            $testUser->setEncodedPassword($this->container, $this->container['tests.user']['password']);
            $testUser->setCreatedAt($this->container['tests.user']['created_at']);
            $testUser->setUpdatedAt($testUser->getCreatedAt());
            $manager->persist($testUser);
            $authors[] = $testUser;

            for ($i=0;$i<20;$i++) {
                $user = new User();
                $user = $user->getFake($this->container);
                $manager->persist($user);
                $authors[] = $user;
            }

            $manager->flush();

        }
}
