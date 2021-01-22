<?php

namespace App\Repository;

use App\Entity\ListElement;
use App\Entity\TodoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListElement[]    findAll()
 * @method ListElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListElement::class);
    }

    // /**
    //  * @return ListElement[] Returns an array of ListElement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListElement
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getElementWithHiggerSort(TodoList $list,int $sort)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.list = :list')
            ->andWhere('e.sort > :sort')
            ->setParameter('list',$list)
            ->setParameter('sort',$sort)
            ->addOrderBy('e.sort','ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getElementWithSort(TodoList $list,int $sort)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.list = :list')
            ->andWhere('e.sort = :sort')
            ->setParameter('list',$list)
            ->setParameter('sort',$sort)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getListElementsSorted(TodoList $list)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.list = :list')
            ->setParameter('list',$list)
            ->addOrderBy('e.sort','ASC')
            ->getQuery()
            ->getResult()
            ;
    }
}
