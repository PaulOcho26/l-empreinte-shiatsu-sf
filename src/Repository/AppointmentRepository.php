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
    public function isSlotAvailable(\DateTimeInterface $requestedStart, int $durationMinutes): bool
    {
        // On calcule la fin (Soin + 15 min de respiration)
        $requestedEnd = (clone $requestedStart)->modify('+' . ($durationMinutes + 15) . ' minutes');

        $qb = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)')
            ->where('a.dateTime < :requestedEnd') // CORRECTION : dateTime
            ->andWhere('a.dateTime > :bufferStart') // CORRECTION : dateTime
            ->setParameter('requestedEnd', $requestedEnd)
            ->setParameter('bufferStart', (clone $requestedStart)->modify('-90 minutes'))
            ->getQuery();

        $count = $qb->getSingleScalarResult();

        return (int)$count === 0;
    }
}