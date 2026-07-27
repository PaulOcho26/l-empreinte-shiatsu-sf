<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\Treatment;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AppointmentController extends AbstractController
{
    #[Route('/reserver/{id}', name: 'app_appointment_new', defaults: ['id' => null])]
    public function new(
        ?Treatment $treatment, 
        Request $request, 
        EntityManagerInterface $entityManager
    ): Response {
        // Sécurité : Seul un utilisateur connecté peut réserver
        $this->denyAccessUnlessGranted('ROLE_USER');

        $appointment = new Appointment();
        
        if ($treatment) {
            $appointment->setTreatment($treatment);
        }

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. On récupère les données saisies
            $appointment = $form->getData();
            $duration = $appointment->getTreatment()->getDuration();
            $requestedDate = $appointment->getDateTime();

            // 2. APPEL AU CERVEAU : Vérification de la disponibilité réelle
            $isAvailable = $entityManager->getRepository(Appointment::class)
                ->isSlotAvailable($requestedDate, $duration);

            if (!$isAvailable) {
                // Si occupé, on ne sauve rien et on avertit Charles
                $this->addFlash('error', 'Ce créneau n’est plus disponible. La Synchronicité demande un autre instant.');
                return $this->redirectToRoute('app_appointment_new');
            }

            // 3. Si libre, on finalise les métadonnées et on enregistre
            $appointment->setPatient($this->getUser());
            $appointment->setStatus('EN ATTENTE'); 

            $entityManager->persist($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Votre demande de rendez-vous a été transmise. Sandrine confirmera la synchronicité prochainement.');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('appointment/new.html.twig', [
            'form' => $form->createView(),
            'selectedTreatment' => $treatment
        ]);
    }
}