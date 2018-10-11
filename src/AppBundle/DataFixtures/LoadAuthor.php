<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadAuthor
 */
class LoadAuthor extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadSite::class,
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $london = new Author();
        $london->setFirstname('Jack');
        $london->setLastname('London');
        $london->setSite($this->getReference('first_site'));

        $tolkien = new Author();
        $tolkien->setFirstname('John');
        $tolkien->setLastname('Tolkien');
        $tolkien->setMiddlename('R.R.');
        $tolkien->setSite($this->getReference('first_site'));

        $howard = new Author();
        $howard->setFirstname('Robert');
        $howard->setLastname('Howard');
        $howard->setMiddlename('Ervin');
        $howard->setSite($this->getReference('second_site'));

        $king = new Author();
        $king->setFirstname('Steven');
        $king->setLastname('King');
        $king->setSite($this->getReference('second_site'));

        $clarke = new Author();
        $clarke->setFirstname('Arthur');
        $clarke->setLastname('Clarke');
        $clarke->setMiddlename('C.');
        $clarke->setSite($this->getReference('second_site'));

        $manager->persist($london);
        $manager->persist($tolkien);
        $manager->persist($howard);
        $manager->persist($king);
        $manager->persist($clarke);

        $manager->flush();

        $this->setReference('author_london', $london);
        $this->setReference('author_tolkien', $tolkien);
        $this->setReference('author_howard', $howard);
        $this->setReference('author_king', $king);
        $this->setReference('author_clarke', $clarke);
    }
}
