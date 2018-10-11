<?php

namespace AppBundle\Security;

use AppBundle\Entity\Site;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 */
class User implements UserInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Site
     */
    private $site;

    /**
     * User constructor.
     * @param string $username
     * @param Site   $site
     */
    public function __construct($username, $site)
    {
        $this->name = $username;
        $this->site = $site;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_API'];
    }

    /**
     * @return void
     */
    public function getPassword()
    {

    }

    /**
     * @return void
     */
    public function getSalt()
    {

    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * @return void
     */
    public function eraseCredentials()
    {

    }

    /**
     * @param string $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->name = $username;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     *
     * @return User
     */
    public function setSite(Site $site)
    {
        $this->site = $site;

        return $this;
    }
}
