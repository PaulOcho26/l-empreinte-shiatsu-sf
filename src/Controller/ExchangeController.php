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
    public function index(
        Request $request, 
        EntityManagerInterface $entityManager, 
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $exchange = new TherapeuticExchange();
        $form = $this->createForm(ExchangeType::class, $exchange);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 1. Création de l'utilisateur (le Patient)
            $user = new User();
            $user->setEmail($form->get('email')->getData());
            $user->setFirstName($form->get('firstName')->getData());
            $user->setLastName($form->get('lastName')->getData());
            $user->setRoles(['ROLE_USER']);
            $user->setWallet('0.00');

            // Hachage sécurisé du mot de passe (Standard professionnel)
            $hashedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);

            // 2. Liaison du message au patient
            $exchange->setPatient($user);
            $exchange->setCreatedAt(new \DateTimeImmutable());
            $exchange->setStatus('EN ATTENTE');

            $entityManager->persist($exchange);
            $entityManager->flush();

            $this->addFlash('success', 'Votre espace patient a été créé avec sérénité. Le soin commence.');

            return $this->redirectToRoute('app_exchange_init');
        }

        return $this->render('exchange/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}