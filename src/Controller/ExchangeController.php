<?php

namespace App\Controller;

use App\Entity\TherapeuticExchange;
use App\Entity\User;
use App\Form\ExchangeType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ExchangeController extends AbstractController
{
    #[Route('/initier-un-echange', name: 'app_exchange_init')]
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository
    ): Response {
        $exchange = new TherapeuticExchange();
        $form = $this->createForm(ExchangeType::class, $exchange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $user = new User();
                $user->setEmail($email);
                $user->setFirstName($form->get('firstName')->getData());
                $user->setLastName($form->get('lastName')->getData());
                $user->setRoles(['ROLE_USER']);
                $user->setWallet('0.00');

                $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
                $entityManager->persist($user);
                $flashMessage = 'Votre espace patient a été créé avec sérénité.';
            } else {
                $flashMessage = 'Heureux de vous revoir. Votre message a été ajouté à votre suivi personnel.';
            }

            $exchange->setPatient($user);
            $exchange->setCreatedAt(new \DateTimeImmutable());
            $exchange->setStatus('EN ATTENTE');

            $entityManager->persist($exchange);
            $entityManager->flush();

            $this->addFlash('success', $flashMessage);

            // REDIRECTION HAUTE COUTURE : Charles arrive directement sur son historique
            return $this->redirectToRoute('app_member_dashboard');
        }

        return $this->render('exchange/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}