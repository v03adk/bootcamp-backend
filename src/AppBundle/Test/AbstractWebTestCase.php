<?php

namespace AppBundle\Test;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    protected function loadFixtures(array $fixtures)
    {
        $loader = new Loader();

        foreach ($fixtures as $className) {
            $fixture = new $className();
            $loader->addFixture($fixture);
        }

        $em = $this->getEntityManager();
        $purger = new ORMPurger($em);
        $executor = new ORMExecutor($em, $purger);
        $executor->execute($loader->getFixtures());
    }

    protected function getEntityManager()
    {
        return $this->client->getContainer()->get('doctrine')->getManager();
    }
}
