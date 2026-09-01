<?php

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /**
     * LOGIQUE MÉTIER : Vérifie si un créneau est libre
     * Prend en compte la durée du soin + 15 min de "respiration"
     */
    public function isSlotAvailable(\DateTimeInterface $requestedDate, int $duration): bool
{
    $requestedEnd = (clone $requestedDate)->modify('+' . $duration . ' minutes');
    $bufferStart = (clone $requestedDate)->modify('-15 minutes');

    /** @var \Doctrine\ORM\QueryBuilder $qb */
    $qb = $this->createQueryBuilder('a');

    $qb->select('COUNT(a.id)')
       ->where('a.dateTime < :requestedEnd')
       ->andWhere('a.dateTime > :bufferStart')
       ->setParameter('requestedEnd', $requestedEnd)
       ->setParameter('bufferStart', $bufferStart);

    $count = $qb->getQuery()->getSingleScalarResult();

    return (int) $count === 0;
}
}