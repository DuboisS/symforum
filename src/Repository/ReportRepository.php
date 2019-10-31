<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    /**
     * @return int Number of untreated reports
     */
    public function countUntreatedReports(): int
    {
        try {
            return (int)$this->createQueryBuilder('r')
                ->select('COUNT(r.id)')
                ->where('r.status is null')
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * @param Message $message
     * @return Message[]
     */
    public function findByMessage(Message $message): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.message = :message')
            ->setParameter('message', $message)
            ->getQuery()
            ->getResult();
    }
}
