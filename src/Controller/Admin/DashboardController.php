<?php

namespace App\Controller\Admin;

use App\Entity\Appointment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // On récupère tous les rendez-vous triés par date
        $appointments = $entityManager->getRepository(Appointment::class)->findBy(
            [], 
            ['dateTime' => 'ASC']
        );

        return $this->render('admin/dashboard/index.html.twig', [
            'appointments' => $appointments, // On envoie la liste à l'index
        ]);
    }
    #[Route('/admin/appointment/validate/{id}', name: 'app_admin_appointment_validate')]
public function validateAppointment(Appointment $appointment, EntityManagerInterface $entityManager): Response
{
    $appointment->setStatus('CONFIRMÉ');
    $entityManager->flush();

    $this->addFlash('success', 'La synchronicité a été confirmée pour ' . $appointment->getPatient()->getFirstName());

    return $this->redirectToRoute('app_admin_dashboard');
}
}