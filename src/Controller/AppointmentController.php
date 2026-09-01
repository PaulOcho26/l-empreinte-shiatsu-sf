<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Treatment;
use App\Form\AppointmentType;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTime;
use DateInterval;

final class AppointmentController extends AbstractController
{
    #[Route('/reserver/{id}', name: 'app_appointment_new', defaults: ['id' => null])]
    #[Route('/reserver/{id}', name: 'app_appointment_new', defaults: ['id' => null])]
    public function new(?Treatment $treatment, Request $request, EntityManagerInterface $entityManager): Response 
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $calendar = [];
        $today = new \DateTime('today');
        $appointmentRepo = $entityManager->getRepository(Appointment::class);
        $duration = $treatment ? $treatment->getDuration() : 60;

        // CONSTRUCTION DE LA GRILLE (Charles ne verra plus le dimanche)
        for ($i = 0; $i < 10; $i++) {
            $currentDate = (clone $today)->add(new \DateInterval("P{$i}D"));
            if ($currentDate->format('N') == 7) continue; // SAUT DU DIMANCHE

            $dayData = ['date' => $currentDate, 'slots' => []];
            $theoreticalSlots = ['09:00', '10:30', '14:00', '15:30', '17:00'];

            foreach ($theoreticalSlots as $time) {
                $slotDateTime = clone $currentDate;
                $slotDateTime->modify($time);
                $available = $appointmentRepo->isSlotAvailable($slotDateTime, $duration);
                $dayData['slots'][] = ['time' => $time, 'full' => $slotDateTime, 'available' => $available];
            }
            $calendar[] = $dayData;
        }

        $appointment = new Appointment();
        if ($treatment) { $appointment->setTreatment($treatment); }
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $requestedDate = $appointment->getDateTime();
            
            // SÉCURITÉ SERVEUR : On refuse le dimanche même si Charles ruse avec l'inspecteur
            if ($requestedDate->format('N') == 7) {
                $this->addFlash('error', 'Le Sanctuaire est clos le dimanche.');
                return $this->redirectToRoute('app_appointment_new', ['id' => $treatment?->getId()]);
            }

            if (!$appointmentRepo->isSlotAvailable($requestedDate, $duration)) {
                $this->addFlash('error', 'Ce créneau est déjà habité.');
                return $this->redirectToRoute('app_appointment_new', ['id' => $treatment?->getId()]);
            }

            $appointment->setPatient($this->getUser())->setStatus('EN ATTENTE');
            $entityManager->persist($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande est transmise au Sanctuaire.');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('appointment/new.html.twig', [
            'form' => $form->createView(),
            'calendar' => $calendar, // VITAL : On envoie la variable calendar
            'selectedTreatment' => $treatment
        ]);
    }
}