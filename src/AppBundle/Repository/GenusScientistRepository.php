<?php

namespace AppBundle\Repository;

use AppBundle\Entity\GenusScientist;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class GenusScientistRepository extends EntityRepository
{
    static public function  createExpertCriteria()
    {
        return $criteria = Criteria::create()
            ->andWhere(Criteria::expr()->gt('yearsStudied',20))
            ->orderBy(['yearsStudied' => 'DESC']);
    }

    public function findAllExperts()
    {
        return $this->createQueryBuilder('genus')
            ->addCriteria(self::createExpertCriteria())
            ->getQuery()
            ->execute();
    }
}
