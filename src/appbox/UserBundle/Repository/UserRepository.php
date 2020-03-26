<?php

namespace appbox\UserBundle\Repository;

/**
 * userRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function userClient()
    {
        $qb = $this->createQueryBuilder('u');
        $qb->where('u.roles = :roles')
        ->setParameter('roles', 'ROLE_USER');
        return $qb->getQuery()->getResult();

    }
}
