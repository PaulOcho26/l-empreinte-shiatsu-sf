<?php

namespace App\Controller;

use App\Repository\TherapeuticExchangeRepository;
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
    public function index(AppointmentRepository $appointmentRepo, TherapeuticExchangeRepository $exchangeRepo): Response
{
    $user = $this->getUser();

    // On récupère les messages liés à cet utilisateur précis
    $exchanges = $exchangeRepo->findBy(['patient' => $user], ['createdAt' => 'DESC']);

    return $this->render('member/index.html.twig', [
        'user' => $user,
        'appointments' => $appointmentRepo->findBy(['patient' => $user], ['dateTime' => 'ASC']),
        'exchanges' => $exchanges, // On envoie la liste ici
    ]);
}
}