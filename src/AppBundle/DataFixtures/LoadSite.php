<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadSite
 */
class LoadSite extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $site = new Site();
        $site->setApiKey('first_site_apikey');
        $site->setName('First site');

        $secondSite = new Site();
        $secondSite->setApiKey('second_site_apikey');
        $secondSite->setName('Second site');

        $manager->persist($site);
        $manager->persist($secondSite);

        $manager->flush();

        $this->setReference('first_site', $site);
        $this->setReference('second_site', $secondSite);
    }
}
