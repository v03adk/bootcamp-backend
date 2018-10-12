<?php

namespace AppBundle\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * Quote
 *
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"read"}},
 *     "denormalization_context"={"groups"={"writeQuote"}}
 * })
 * @ApiFilter(SearchFilter::class, properties={"author": "exact"})
 * @ORM\Table(name="quote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuoteRepository")
 */
class Quote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("read")
     */
    private $id;

    /**
     * @var Author
     *
     * @ORM\ManyToOne(targetEntity="Author")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     * @Groups({"read", "writeQuote"})
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="quote", type="string", length=1000)
     * @Assert\NotBlank
     * @Groups({"read", "writeQuote"})
     */
    private $quote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="Site")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id", nullable=false)
     */
    private $site;

    /**
     * Quote constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author.
     *
     * @param Author $author
     *
     * @return Quote
     */
    public function setAuthor(Author $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set quote.
     *
     * @param string $quote
     *
     * @return Quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Get quote.
     *
     * @return string
     */
    public function getQuote()
    {
        return $this->quote;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
     * @return Quote
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }
}
