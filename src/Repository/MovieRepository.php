<?php

namespace App\Repository;

use App\Entity\Movie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Movie>
 *
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly AuthorizationCheckerInterface $checker)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findByRated(?User $user): iterable
    {
        if ($this->checker->isGranted('ROLE_ADMIN')) {
            return $this->findAll();
        }

        $age = $user->getAge();
        $ratings = match (true) {
            $age < 13, null === $age => ['G'],
            $age < 17 => ['PG', 'PG-13', 'G'],
            default => ['R', 'NC-17', 'PG', 'PG-13', 'G'],
        };

        $qb = $this->createQueryBuilder('m');

        return $qb->andWhere($qb->expr()->in('m.rated', $ratings))
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Movie[] Returns an array of Movie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Movie
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
