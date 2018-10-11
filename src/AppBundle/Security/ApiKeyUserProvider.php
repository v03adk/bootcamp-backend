<?php

namespace AppBundle\Security;

use AppBundle\Entity\Site;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ApiKeyUserProvider
 */
class ApiKeyUserProvider implements UserProviderInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ApiKeyUserProvider constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param $apiKey
     * @return Site|null
     */
    public function getUsernameForApiKey($apiKey)
    {
        return $this->em->getRepository(Site::class)->findOneBy(['apiKey' => $apiKey]);
    }

    /**
     * @param string $site
     * @return User|UserInterface
     */
    public function loadUserByUsername($site)
    {
        return new User(
            $site->getApiKey(),
            $site
        );
    }

    /**
     * @param UserInterface $user
     * @return UserInterface|void
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        return 'AppBundle\Security\User' === $class;
    }
}
