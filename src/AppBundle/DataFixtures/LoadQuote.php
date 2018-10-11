<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Quote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadQuote
 */
class LoadQuote extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            LoadAuthor::class,
        ];
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $quote = new Quote();
        $quote->setAuthor($this->getReference('author_london'));
        $quote->setSite($this->getReference('first_site'));
        $quote->setQuote('Martin Eden is not about me');

        $quote2 = new Quote();
        $quote2->setAuthor($this->getReference('author_london'));
        $quote2->setSite($this->getReference('first_site'));
        $quote2->setQuote('Smoke Bellew is a cool book for teenagers');

        $quote3 = new Quote();
        $quote3->setAuthor($this->getReference('author_tolkien'));
        $quote3->setSite($this->getReference('first_site'));
        $quote3->setQuote('The Hobbit is great');

        $quote4 = new Quote();
        $quote4->setAuthor($this->getReference('author_tolkien'));
        $quote4->setSite($this->getReference('first_site'));
        $quote4->setQuote('The Lord of the Rings is more greate');

        $quote5 = new Quote();
        $quote5->setAuthor($this->getReference('author_tolkien'));
        $quote5->setSite($this->getReference('first_site'));
        $quote5->setQuote('The Silmarillion is simply the best');

        $quote6 = new Quote();
        $quote6->setAuthor($this->getReference('author_king'));
        $quote6->setSite($this->getReference('second_site'));
        $quote6->setQuote('I am the king of a horror');

        $quote7 = new Quote();
        $quote7->setAuthor($this->getReference('author_howard'));
        $quote7->setSite($this->getReference('second_site'));
        $quote7->setQuote('Conan is the beast');

        $quote8 = new Quote();
        $quote8->setAuthor($this->getReference('author_howard'));
        $quote8->setSite($this->getReference('second_site'));
        $quote8->setQuote('Long life new king - Conan the Barbarian');

        $quote9 = new Quote();
        $quote9->setAuthor($this->getReference('author_clarke'));
        $quote9->setSite($this->getReference('second_site'));
        $quote9->setQuote('Mysteries of the First Kind: something that was once utterly baffling, but is now completely understood.');

        $quote10 = new Quote();
        $quote10->setAuthor($this->getReference('author_clarke'));
        $quote10->setSite($this->getReference('second_site'));
        $quote10->setQuote('Mysteries of the Second Kind: Something that is currently not fully understood and can be in the future.');

        $quote11 = new Quote();
        $quote11->setAuthor($this->getReference('author_clarke'));
        $quote11->setSite($this->getReference('second_site'));
        $quote11->setQuote('Mysteries of the Third Kind: Something of which we have no understanding.');

        $manager->persist($quote);
        $manager->persist($quote2);
        $manager->persist($quote3);
        $manager->persist($quote4);
        $manager->persist($quote5);
        $manager->persist($quote6);
        $manager->persist($quote7);
        $manager->persist($quote8);
        $manager->persist($quote9);
        $manager->persist($quote10);
        $manager->persist($quote11);


        $manager->flush();
    }
}
