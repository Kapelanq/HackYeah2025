<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends ServiceEntityRepository<Users>
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function loginUser(string $login, string $password): ?Users
    {
        $em = $this->getEntityManager();
        return $em->createQueryBuilder()
            ->select('u')
            ->from(Users::class, 'u')
            ->where('u.username = :login')
            ->andWhere('u.password = :password')
            ->setParameter('login', $login)
            ->setParameter('password', $password)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findUserById(int $id): ?Users
    {
        $em = $this->getEntityManager();
        return $em->createQueryBuilder()
            ->select('u')
            ->from(Users::class, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function addPoints(Users $user, int $points): void
    {
        $user->setPoints($user->getPoints() + $points);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }


    //    /**
    //     * @return Users[] Returns an array of Users objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Users
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
