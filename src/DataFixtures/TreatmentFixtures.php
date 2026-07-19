<?php

namespace App\DataFixtures;

use App\Entity\Treatment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class TreatmentFixtures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger
    ) {}

    public function load(ObjectManager $manager): void
    {
        $treatments = [
            [
                'title' => 'Shiatsu Traditionnel Namikoshi',
                'duration' => 70,
                'price' => '75.00',
                'type' => 'Shiatsu',
                'benefits' => 'Rééquilibrage énergétique, libération des tensions musculaires et apaisement du système nerveux par pressions digitales.'
            ],
            [
                'title' => 'MTC & Ventouses',
                'duration' => 70,
                'price' => '80.00',
                'type' => 'Médecine Traditionnelle Chinoise',
                'benefits' => 'Approche thérapeutique globale utilisant les principes de la MTC et la pose de ventouses pour stimuler la circulation du sang et du Qi.'
            ],
            [
                'title' => 'Réflexologie Plantaire',
                'duration' => 60,
                'price' => '70.00',
                'type' => 'Réflexologie',
                'benefits' => 'Soin par zones réflexes des pieds visant à relancer les fonctions vitales et apporter une profonde détente.'
            ],
        ];

        foreach ($treatments as $data) {
            $treatment = new Treatment();
            $treatment->setTitle($data['title']);
            $treatment->setSlug($this->slugger->slug($data['title'])->lower());
            $treatment->setDuration($data['duration']);
            $treatment->setPrice($data['price']); // Format string pour le type Decimal
            $treatment->setProtocolBenefits($data['benefits']);
            $treatment->setType($data['type']);
            $treatment->setIsActive(true);

            $manager->persist($treatment);
        }

        $manager->flush();
    }
}