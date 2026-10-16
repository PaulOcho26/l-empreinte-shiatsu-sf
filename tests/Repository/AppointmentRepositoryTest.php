<?php

namespace App\Tests\Repository;

use App\Entity\Appointment;
use App\Entity\Treatment;
use App\Entity\User;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class AppointmentRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->entityManager->getConnection()->beginTransaction();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager->getConnection()->isTransactionActive()) {
            $this->entityManager->getConnection()->rollBack();
        }

        parent::tearDown();
    }

    public function testSlotAvailabilityRespectsTreatmentDurationAndFifteenMinuteBuffer(): void
    {
        $user = (new User())
            ->setEmail('test@example.com')
            ->setPassword('test')
            ->setFirstName('Test')
            ->setLastName('Patient')
            ->setRoles([])
            ->setWallet('0.00');

        $treatment = (new Treatment())
            ->setTitle('Test 70 min')
            ->setSlug('test-70')
            ->setDuration(70)
            ->setPrice('85.00')
            ->setProtocolBenefits('Test')
            ->setType('Soin Individuel')
            ->setIsActive(true);

        $appointment = (new Appointment())
            ->setDateTime(new \DateTimeImmutable('2026-10-20 10:30'))
            ->setStatus('CONFIRMÉ')
            ->setPatient($user)
            ->setTreatment($treatment);

        $this->entityManager->persist($user);
        $this->entityManager->persist($treatment);
        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        /** @var AppointmentRepository $repository */
        $repository = $this->entityManager->getRepository(Appointment::class);

        self::assertTrue($repository->isSlotAvailable(new \DateTimeImmutable('2026-10-20 09:15'), 60));
        self::assertFalse($repository->isSlotAvailable(new \DateTimeImmutable('2026-10-20 09:16'), 60));
        self::assertFalse($repository->isSlotAvailable(new \DateTimeImmutable('2026-10-20 11:54'), 60));
        self::assertTrue($repository->isSlotAvailable(new \DateTimeImmutable('2026-10-20 11:55'), 60));
    }
}
