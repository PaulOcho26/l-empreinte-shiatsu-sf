<?php

namespace App\Form;

use App\Entity\TherapeuticExchange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // --- IDENTITÉ (mapped => false) ---
            ->add('firstName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Le prénom est requis.')
                ]
            ])
            ->add('lastName', TextType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'Le nom est requis.')
                ]
            ])
            ->add('email', EmailType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(message: 'L\'email est requis pour sécuriser l\'échange.')
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                'autocomplete' => 'new-password', // Empêche le remplissage automatique par le navigateur
                'class' => 'w-full bg-transparent border-b border-zen-ink/10 py-3 font-eb-garamond text-lg outline-none focus:border-zen-forest transition-all'
                ],
                'constraints' => [ /* vos contraintes */ ]
            ])

            // --- MESSAGE (Mappé) ---
            ->add('subject', TextType::class, [
                'label' => 'Sujet de votre démarche'
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Vos mots',
                'attr' => ['rows' => 6]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TherapeuticExchange::class,
        ]);
    }
}