<?php

namespace App\Repository;

use App\Entity\Notes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notes>
 *
 * @method Notes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notes[]    findAll()
 * @method Notes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notes::class);
    }

    public function save(Notes $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notes $entity, bool $flush): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // retourne les notes d'un eleve
    public function findByStudentId($value): array
    {
            return $this->createQueryBuilder('n')
                    ->andWhere('n.student_id = :val')
                    ->setParameter('val', $value)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    // retourne les notes d'une classe
    public function findByClassId($value): array
    {
            return $this->createQueryBuilder('n')
                    ->andWhere('n.class_id = :val')
                    ->setParameter('val', $value)
                    ->orderBy('n.student_id', 'ASC')
                    ->getQuery()
                    ->getResult()
                    ;
    }

    //retourne les notes d'un eleve donné dans une classe donnée
    public function findOneByClassId_StudentId($c_id ,$s_id): ?Notes
    {
            return $this->createQueryBuilder('tab')
                    ->andWhere('tab.class_id = :val' , 'tab.student_id = :vale')
                    ->setParameters([
                                      'val' => $c_id,
                                      'vale' => $s_id,
                                   ])
                    ->getQuery()
                    ->getOneOrNullResult()
                    ;
    }

    // retourne le (s) etudiant(s) qui ont la note passer en argument
    public function findStudentByNote($class_id, $note): array
    {
            return $this->createQueryBuilder('n')
                        ->andWhere('n.class_id = :val')
                        ->andWhere('n.note = :note')
                        ->setParameter('val', $class_id)
                        ->setParameter('note', $note)
                        ->getQuery()
                        ->getResult()

                    ;
    }

    // retourne le (s) etudiant(s) qui ont le nombre d'absences passe en arguments
    public function findStudentByAbsence($class_id, $nbr_absence): array
    {
            return $this->createQueryBuilder('n')
                        ->andWhere('n.class_id = :val')
                        ->andWhere('n.nbr_absences = :abs')
                        ->setParameter('val', $class_id)
                        ->setParameter('abs', $nbr_absence)
                        ->getQuery()
                        ->getResult()

                    ;
    }




//    /**
//     * @return Notes[] Returns an array of Notes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Notes
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
