<?php

namespace appbox\voyageBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * voyageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class voyageRepository extends \Doctrine\ORM\EntityRepository
{
    public function indexvoyage()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->orderBy('v.datefin', 'desc')
            ->setMaxResults(5);
        return $qb->getQuery()->getResult();

    }
    public function indexpromovoyage()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->where('v.promo = 1')
            ->orderBy('v.datefin', 'desc')
            ->setMaxResults(4);
        return $qb->getQuery()->getResult();

    }
    public function indexdestination(){
        $qb = $this->createQueryBuilder('v');
        $qb->groupBy('v.destination');
        return $qb->getQuery()->getResult();
    }
    public function treevoyage()
    {
        $qb = $this->createQueryBuilder('v');
        $qb->orderBy('v.datefin','desc')
           ->setMaxResults(3);
        return $qb->getQuery()->getResult();
    }
    public function pagination($page,$nbrmaxvoyage){
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas');
        }

        $qb = $this->createQueryBuilder('v')
            ->orderBy('v.datefin', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbrmaxvoyage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbrmaxvoyage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas.'); // page 404, sauf pour la premi�re page
        }

        return $paginator;
    }
    public function paginationpromo($page,$nbrmaxvoyage){
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas');
        }

        $qb = $this->createQueryBuilder('v')
            ->where('v.promo = 1 ')
            ->orderBy('v.datefin', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbrmaxvoyage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbrmaxvoyage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas.'); // page 404, sauf pour la premi�re page
        }

        return $paginator;
    }
    public function paginationLastmunite($page,$nbrmaxvoyage){
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas');
        }

        $qb = $this->createQueryBuilder('v')
            ->where("v.typepromo = 'Last Munite' ")
            ->orderBy('v.datefin', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbrmaxvoyage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbrmaxvoyage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas.'); // page 404, sauf pour la premi�re page
        }

        return $paginator;
    }
    public function typepagination($page,$nbrmaxvoyage,$type){
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas');
        }

        $qb = $this->createQueryBuilder('v')
            ->where('v.type = :type')
            ->setParameter('type',$type)
            ->orderBy('v.datefin', 'ASC');

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbrmaxvoyage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbrmaxvoyage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas.'); // page 404, sauf pour la premi�re page
        }

        return $paginator;
    }
    public function recherchepagination($page,$nbrmaxvoyage,$nom,$destination,$type){
        if (!is_numeric($page)) {
            throw new InvalidArgumentException(
                'La valeur de l\'argument $page est incorrecte (valeur : ' . $page . ').'
            );
        }

        if ($page < 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas');
        }

        $qb = $this->createQueryBuilder('v')
            ->orderBy('v.datefin', 'ASC');
         if($nom){
             $qb->where('v.titre LIKE :nom')->setParameter('nom','%'.$nom.'%');
         }if($destination){
            $qb->where('v.destination = :nom')->setParameter('nom',$destination);
        }if($type){
            $qb->where('v.type = :nom')->setParameter('nom',$type);
        }

        $query = $qb->getQuery();

        $premierResultat = ($page - 1) * $nbrmaxvoyage;
        $query->setFirstResult($premierResultat)->setMaxResults($nbrmaxvoyage);
        $paginator = new Paginator($query);

        if ( ($paginator->count() <= $premierResultat) && $page != 1) {
            throw new NotFoundHttpException('La page demand�e n\'existe pas.'); // page 404, sauf pour la premi�re page
        }

        return $paginator;
    }
}
