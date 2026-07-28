<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mon-espace')]
#[IsGranted('ROLE_USER')]
final class MemberController extends AbstractController
{
    #[Route('', name: 'app_member_dashboard')]
    public function index(AppointmentRepository $appointmentRepository): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        // On récupère les rendez-vous du patient, triés par date (du plus proche au plus lointain)
        $appointments = $appointmentRepository->findBy(
            ['patient' => $user],
            ['dateTime' => 'ASC']
        );

        return $this->render('member/index.html.twig', [
            'user' => $user,
            'appointments' => $appointments,
        ]);
    }
}