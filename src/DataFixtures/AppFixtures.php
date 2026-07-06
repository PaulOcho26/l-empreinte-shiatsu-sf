<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Treatment;
use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        // On injecte le service pour hacher les mots de passe
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. CRÉATION DE L'ADMIN (Sandrine)
        $admin = new User();
        $admin->setEmail('sandrine@l-empreinte.fr');
        $admin->setFirstName('Sandrine');
        $admin->setLastName('Tribhou');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setWallet('0.00');
        $password = $this->hasher->hashPassword($admin, '182618');
        $admin->setPassword($password);
        $manager->persist($admin);

        // 2. CRÉATION D'UN PATIENT DE TEST (Léa)
        $patient = new User();
        $patient->setEmail('lea@exemple.com');
        $patient->setFirstName('Léa');
        $patient->setLastName('Patient');
        $patient->setRoles(['ROLE_USER']);
        $patient->setWallet('50.00'); // On lui offre 50€ sur son wallet
        $password = $this->hasher->hashPassword($patient, 'password');
        $patient->setPassword($password);
        $manager->persist($patient);

        // 3. CRÉATION D'UN SOIN (L'Offre)
        $treatment = new Treatment();
        $treatment->setTitle('Shiatsu Traditionnel (Complet)');
        $treatment->setSlug('shiatsu-traditionnel-complet');
        $treatment->setDuration(70);
        $treatment->setPrice('75.00');
        $treatment->setProtocolBenefits('Issu de la méthode Namikoshi, ce protocole cible les nerfs et les muscles pour dénouer les tensions physiologiques.');
        $treatment->setType('Unique');
        $treatment->setIsActive(true);
        $manager->persist($treatment);

        // 4. CRÉATION D'UN ARTICLE (Le Savoir)
        $article = new Article();
        $article->setTitle('L’Équilibre Hivernal');
        $article->setSlug('equilibre-hivernal');
        $article->setContent('L’hiver en médecine traditionnelle chinoise est lié à l’élément Eau. C’est la saison du retrait...');
        $article->setCategory('Sanctuaire');
        $article->setAuthorName('Sandrine Tribhou');
        $article->setCreatedAt(new \DateTimeImmutable());
        $article->setIsPublished(true);
        $article->setAuthor($admin); // Sandrine est l'auteur
        $manager->persist($article);

        // On envoie tout en base de données
        $manager->flush();
    }
}