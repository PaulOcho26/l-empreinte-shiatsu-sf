<?php

namespace App\DataFixtures;

use App\Entity\Lexicon;
use App\Entity\User; // Import indispensable
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface; // Import indispensable
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class LexiconFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private SluggerInterface $slugger) {}

    public function load(ObjectManager $manager): void
    {
        // On récupère Sandrine grâce à sa référence
        $author = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE, User::class);

        $terms = [
            [
                'term' => 'Shiatsu',
                'definition' => 'Le Shiatsu est une thérapie manuelle d’origine japonaise qui consiste en l’application de pressions sur l’ensemble du corps avec les pouces et les paumes, sans usage d’instruments, afin de corriger les dysfonctionnements internes et de maintenir la santé.'
            ],
            [
                'term' => 'Qi',
                'definition' => 'Dans la cosmologie chinoise, le Qi désigne le souffle vital, l’énergie fondamentale qui anime tout être vivant et circule de manière invisible dans l’organisme.'
            ],
            [
                'term' => 'Méridiens',
                'definition' => 'Canaux subtils interconnectés à travers lesquels circulent le sang et l’énergie (Qi). Ils relient les organes internes à la surface du corps et aux extrémités.'
            ],
            [
                'term' => 'Holistique',
                'definition' => 'Approche thérapeutique qui considère l’être humain comme une unité indissociable du corps, de l’esprit et de l’environnement, traitant la racine du déséquilibre plutôt que le symptôme seul.'
            ]
        ];
        foreach ($terms as $data) {
            $lexicon = new Lexicon();
            $lexicon->setTerm($data['term']);
            $lexicon->setSlug($this->slugger->slug($data['term'])->lower());
            $lexicon->setDefinition($data['definition']);
            
            // On assigne l'auteur ici !
            $lexicon->setAuthor($author);
            
            $manager->persist($lexicon);
        }

        $manager->flush();
    }

    // On force Symfony à charger UserFixtures AVANT LexiconFixtures
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}