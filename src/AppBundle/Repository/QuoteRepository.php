<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Quote;
use AppBundle\Entity\Site;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * QuoteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class QuoteRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param Site $site
     * @return Quote|null
     */
    public function getRandomQuote(Site $site)
    {
        $entityManager = $this->getEntityManager();
        $sql = 'SELECT q.* FROM quote q WHERE q.site_id = ? ORDER BY RAND() LIMIT 1';
        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata(Quote::class, 'q');

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameter(1, $site->getId());

        return $query->getOneOrNullResult();
    }
}
