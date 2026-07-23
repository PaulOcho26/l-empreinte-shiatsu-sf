<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Treatment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('date_time', null, [
            'label' => 'Date et heure du soin',
            'widget' => 'single_text',
            'attr' => ['class' => 'bg-transparent border-b border-zen-ink/10 py-2 outline-none focus:border-zen-forest font-eb-garamond']
        ])
        ->add('treatment', null, [
            'label' => 'Prestation souhaitée',
            'choice_label' => 'title', // On affiche le titre du soin (Shiatsu, etc.)
            'attr' => ['class' => 'bg-transparent border-b border-zen-ink/10 py-2 outline-none focus:border-zen-forest font-eb-garamond']
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
