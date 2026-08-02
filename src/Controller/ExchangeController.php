<?php

namespace App\Controller;

use App\Entity\TherapeuticExchange;
use App\Entity\User; // Vital : pour créer le patient
use App\Form\ExchangeType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Vital : pour le hachage
use Symfony\Component\Routing\Attribute\Route;

final class ExchangeController extends AbstractController
{
    #[Route('/initier-un-echange', name: 'app_exchange_init')]
    #[Route('/initier-un-echange', name: 'app_exchange_init')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        \App\Repository\UserRepository $userRepository // Injectez le Repo ici
    ): Response {
        $exchange = new TherapeuticExchange();
        $form = $this->createForm(ExchangeType::class, $exchange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            // 1. On vérifie si l'utilisateur existe déjà
            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                // 2. S'il n'existe pas, on le crée (Inscription)
                $user = new User();
                $user->setEmail($email);
                $user->setFirstName($form->get('firstName')->getData());
                $user->setLastName($form->get('lastName')->getData());
                $user->setRoles(['ROLE_USER']);
                $user->setWallet('0.00');
                $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
                $entityManager->persist($user);
                $flash = 'Bienvenue. Votre espace patient a été créé.';
            } else {
                // 3. S'il existe (Comme Charles), on l'utilise simplement (Reconnaissance)
                $flash = 'Heureux de vous revoir. Votre message a été ajouté à votre suivi.';
            }

            // 4. On lie l'échange au patient (nouveau ou existant)
            $exchange->setPatient($user);
            $exchange->setCreatedAt(new \DateTimeImmutable());
            $exchange->setStatus('EN ATTENTE');

            $entityManager->persist($exchange);
            $entityManager->flush();

            $this->addFlash('success', $flash);
            return $this->redirectToRoute('app_member_dashboard');
        }

        return $this->render('exchange/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}