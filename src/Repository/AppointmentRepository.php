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
        $bufferMinutes = 15;

        $requestedStart = \DateTimeImmutable::createFromInterface($requestedDate);
        $requestedEnd = $requestedStart->modify("+{$duration} minutes");
        $requestedEndWithBuffer = $requestedEnd->modify("+{$bufferMinutes} minutes");

        $dayStart = $requestedStart->setTime(0, 0);
        $dayEnd = $dayStart->modify('+1 day');

        $appointments = $this->createQueryBuilder('a')
            ->leftJoin('a.treatment', 't')
            ->addSelect('t')
            ->where('a.dateTime >= :dayStart')
            ->andWhere('a.dateTime < :dayEnd')
            ->setParameter('dayStart', $dayStart)
            ->setParameter('dayEnd', $dayEnd)
            ->getQuery()
            ->getResult();

        foreach ($appointments as $appointment) {
            $existingDuration = $appointment->getTreatment()?->getDuration();

            if ($existingDuration === null) {
                continue;
            }

            $existingStart = $appointment->getDateTime();
            $existingEndWithBuffer = $existingStart
                ->modify('+' . ($existingDuration + $bufferMinutes) . ' minutes');

            if (
                $requestedStart < $existingEndWithBuffer
                && $requestedEndWithBuffer > $existingStart
            ) {
                return false;
            }
        }

        return true;
    }

}
