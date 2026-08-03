<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Lexicon;
use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // 1. CRÉATION DE L'ADMIN (Sandrine)
        $admin = new User();
        $admin->setEmail('sandrine@l-empreinte.fr');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstName('Sandrine');
        $admin->setLastName('Tribhou');
        $admin->setWallet(0.00);
        $admin->setPassword($this->hasher->hashPassword($admin, 'zen-password'));
        $manager->persist($admin);

        // 2. CRÉATION D'UN PATIENT TEST (Charles)
        $charles = new User();
        $charles->setEmail('charles@exemple.com');
        $charles->setRoles(['ROLE_USER']);
        $charles->setFirstName('Charles');
        $charles->setLastName('Debord');
        $charles->setWallet(100.00);
        $charles->setPassword($this->hasher->hashPassword($charles, 'charles-password'));
        $manager->persist($charles);

        // 3. LE LEXIQUE (10 pages)
        $terms = [
            'Qi' => 'Le souffle vital, l\'énergie qui circule dans chaque méridien pour animer le corps et l\'esprit.',
            'Hara' => 'Le centre de gravité situé dans l\'abdomen, considéré comme la source de l\'énergie vitale.',
            'Méridiens' => 'Canaux invisibles où circule le Qi, reliant les organes à la surface du corps.',
            'Tsubo' => 'Points de pression spécifiques situés le long des méridiens, véritables portes d\'accès.',
            'Ying Yang' => 'Les deux forces opposées et complémentaires dont l\'équilibre définit la santé.',
            'Kyo' => 'Un état de vide ou de manque énergétique dans un méridien, nécessitant tonification.',
            'Jitsu' => 'Un état de plénitude ou d\'excès énergétique, nécessitant une dispersion.',
            'Namikoshi' => 'La lignée originelle du Shiatsu, mettant l\'accent sur l\'anatomie et la physiologie.',
            'Makko Ho' => 'Série d\'étirements japonais conçus pour libérer la circulation dans les méridiens.',
            'Do In' => 'L\'auto-massage et les exercices de santé pour entretenir son propre flux.'
        ];

        foreach ($terms as $name => $definition) {
            $lex = new Lexicon();
            $lex->setTerm($name);
            $lex->setSlug(strtolower($this->slugger->slug($name)));
            $lex->setDefinition($definition);
            $lex->setAuthor($admin);
            $manager->persist($lex);
        }

        // 4. LES SOINS (5 pages)
        $needs = [
            'Sérénité Mentale' => 'Accompagnement spécifique du stress par le Shiatsu crânien.',
            'Harmonie Digestive' => 'Protocole ciblé sur le Hara pour libérer les tensions abdominales.',
            'Souffle du Dos' => 'Libération des tensions vertébrales et des blocages musculaires.',
            'Maternité Douce' => 'Un accompagnement bienveillant pour la future maman.',
            'Récupération Vitale' => 'Soin dynamisant pour les périodes de fatigue.'
        ];

        foreach ($needs as $title => $benefit) {
            $treatment = new Treatment();
            $treatment->setTitle($title);
            $treatment->setSlug(strtolower($this->slugger->slug($title)));
            $treatment->setProtocolBenefits($benefit);
            $treatment->setPrice(85.00);
            $treatment->setDuration(75);
            $treatment->setType('Soin Individuel'); // FIX: Respect de la contrainte NOT NULL
            $treatment->setIsActive(true);
            $manager->persist($treatment);
        }

        // 5. ARTICLES DU SANCTUAIRE (Génération de 10 pages)
        $categories = ['Saisonalité', 'Philosophie', 'Nutrition'];
        for ($i = 1; $i <= 10; $i++) {
            $article = new \App\Entity\Article();
            $article->setTitle("Enseignement de Shiatsu n°$i");
            $article->setSlug("enseignement-$i");
            $article->setContent("Le Shiatsu libère le Qi à travers les Méridiens. Le Hara est le centre du souffle.");
            $article->setCategory($categories[array_rand($categories)]);
            $article->setAuthorName("Sandrine Tribhou"); // FIX 1 : Nom de l'auteur
            $article->setAuthor($admin);             // FIX 2 : Lien avec l'entité User (Sandrine)
            $article->setIsPublished(true);
            $article->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($article);
        }

        $manager->flush();
    }
}