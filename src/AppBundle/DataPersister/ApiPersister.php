<?php

namespace AppBundle\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use AppBundle\Entity\Author;
use AppBundle\Entity\Quote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ApiPersister
 * Adds Site to entities that can be created via API post: Author, Quote
 */
final class ApiPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ApiPersister constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface  $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public function supports($data): bool
    {
        return in_array(get_class($data),[Author::class, Quote::class]);
    }

    /**
     * @param Author|Quote $data
     * @return Author|Quote
     */
    public function persist($data)
    {
        $data->setSite($this->tokenStorage->getToken()->getUser()->getSite());

        $this->em->persist($data);
        $this->em->flush();

        return $data;
    }

    /**
     * @param Author|Quote $data
     */
    public function remove($data)
    {
        $this->em->remove($data);
        $this->em->flush();
    }
}
