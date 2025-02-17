<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Document>
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Document::class);
    }


    /**
     * Finds all documents for a given student
     *
     * @param int $id The student ID
     *
     * @return array An array of Document objects
     */
    public function findByStudentId(int $id): array
    {
        return $this->createQueryBuilder('d')
            ->where('d.student = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
    }
    /**
     * Retrieves a simplified array of documents for a given student.
     *
     * Each document is represented as an associative array containing
     * selected fields such as 'id' and 'name'.
     *
     * @param int $id The student ID
     *
     * @return array An array of associative arrays representing simplified documents
     */

    public function findByStudentIdSimplified(int $id): array
    {
        $result = $this->findByStudentId($id);
        $simplified = array_map(function ($item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                // Add other fields as needed
            ];
        }, $result);
        return $simplified;
    }


    //    /**
//     * @return Document[] Returns an array of Document objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    //    public function findOneBySomeField($value): ?Document
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
