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
    // On crée un objet DateTime mutable pour éviter les erreurs d'interface
    $start = \DateTime::createFromInterface($requestedStart);
    
    // Calcul de la fin (Soin + 15 min de respiration)
    $requestedEnd = (clone $start)->modify('+' . ($durationMinutes + 15) . ' minutes');

    // Marge de sécurité de 90 min avant (préparation du cabinet)
    $bufferStart = (clone $start)->modify('-90 minutes');

    $qb = $this->createQueryBuilder('a')
        ->select('COUNT(a.id)')
        ->where('a.dateTime < :requestedEnd')
        ->andWhere('a.dateTime > :bufferStart')
        ->setParameters([
            'requestedEnd' => $requestedEnd,
            'bufferStart' => $bufferStart,
        ]);

    return (int) $qb->getQuery()->getSingleScalarResult() === 0;
}
}