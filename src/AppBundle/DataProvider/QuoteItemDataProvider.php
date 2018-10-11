<?php

namespace AppBundle\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use AppBundle\Entity\Quote;
use AppBundle\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class QuoteItemDataProvider
 * Custom Quote provider to provide random quote
 */
final class QuoteItemDataProvider implements ItemDataProviderInterface
{
    /**
     * @var QuoteRepository
     */
    private $repository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * QuoteItemDataProvider constructor.
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface  $tokenStorage
     */
    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->repository = $em->getRepository(Quote::class);
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string      $resourceClass
     * @param int|string  $id
     * @param string|null $operationName
     * @param array       $context
     *
     * @return Quote|null
     * @throws ResourceClassNotSupportedException
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        if (Quote::class !== $resourceClass) {
            throw new ResourceClassNotSupportedException();
        }

        $site = $this->tokenStorage->getToken()->getUser()->getSite();

        if ('random' === $id) {
            return $this->repository->getRandomQuote($site);
        }

        return $this->repository->findOneBy(['id' => $id, 'site' => $site]);
    }
}
