<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Treatment;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // 1. Le choix du soin (Dynamique depuis la BDD)
            ->add('treatment', EntityType::class, [
                'class' => Treatment::class,
                'choice_label' => function (Treatment $treatment) {
                    return $treatment->getTitle() . ' (' . $treatment->getDuration() . ' min)';
                },
                'query_builder' => function (EntityRepository $er) {
                    return $repo = $er->createQueryBuilder('t')
                        ->where('t.isActive = :active')
                        ->setParameter('active', true)
                        ->orderBy('t.title', 'ASC');
                },
                'label' => 'Prestation souhaitée',
                'placeholder' => 'Sélectionner un rituel de soin...',
                'attr' => ['class' => 'w-full bg-transparent border-b border-zen-ink/10 py-3 font-eb-garamond text-lg outline-none focus:border-zen-forest transition-all appearance-none']
            ])

            // 2. La date et l'heure
            ->add('date_time', DateTimeType::class, [
                'label' => 'L\'ancrage horaire',
                'widget' => 'single_text',
                'attr' => ['class' => 'w-full bg-transparent border-b border-zen-ink/10 py-3 font-eb-garamond text-lg outline-none focus:border-zen-forest transition-all']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}