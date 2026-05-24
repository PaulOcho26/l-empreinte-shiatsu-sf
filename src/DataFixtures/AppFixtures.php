<?php

namespace App\DataFixtures;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $services = [
            [
                'name' => 'Shiatsu Traditionnel',
                'description' => 'Séance complète pour harmoniser l\'énergie vitale et libérer les tensions.',
                'duration' => 60,
                'price' => 60.0,
                'slug' => 'shiatsu-traditionnel'
            ],
            [
                'name' => 'Approche Thérapeutique',
                'description' => 'Accompagnement ciblé utilisant les principes de la Médecine Traditionnelle Chinoise.',
                'duration' => 75,
                'price' => 70.0,
                'slug' => 'shiatsu-approche-therapeutique'
            ],
            [
                'name' => 'Protocole Spécifique',
                'description' => 'Séance courte focalisée sur une zone ou une problématique précise (stress, dos).',
                'duration' => 45,
                'price' => 45.0,
                'slug' => 'protocole-specifique'
            ],
        ];

        foreach ($services as $data) {
            $service = new Service();
            $service->setName($data['name']);
            $service->setDescription($data['description']);
            $service->setDuration($data['duration']);
            $service->setPrice($data['price']);
            $service->setSlug($data['slug']);
            
            $manager->persist($service);
        }

        $manager->flush();
    }
}