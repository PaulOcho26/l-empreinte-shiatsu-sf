<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    // Ce nom servira de clé pour les autres fixtures
    public const ADMIN_USER_REFERENCE = 'admin-sandrine';

    public function __construct(private UserPasswordHasherInterface $hasher) {}

public function load(ObjectManager $manager): void
    {
        // 1. Création de Sandrine (L'Admin)
        $admin = new User();
        $admin->setEmail('sandrine@l-empreinte.fr');
        $admin->setFirstName('Sandrine');
        $admin->setLastName('Tribhou');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setWallet('0.00');
        $admin->setPassword($this->hasher->hashPassword($admin, 'Azerty123!'));

        $manager->persist($admin);

        // 2. Création de Charles (Le Patient permanent)
        $charles = new User();
        $charles->setEmail('charles.debord@live.fr');
        $charles->setFirstName('Charles');
        $charles->setLastName('Debord');
        $charles->setRoles(['ROLE_USER']);
        $charles->setWallet('0.00');
        $charles->setPassword($this->hasher->hashPassword($charles, 'Azerty123!'));

        $manager->persist($charles);

        // 3. ENREGISTREMENT FINAL (On flush une seule fois pour tout le monde)
        $manager->flush();

        // 4. RÉFÉRENCE (On garde la référence de Sandrine pour le Lexique/Articles)
        $this->addReference(self::ADMIN_USER_REFERENCE, $admin);
    }
}