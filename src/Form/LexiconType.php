<?php

namespace App\Form;

use App\Entity\Lexicon;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LexiconType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('term', null, [
                'label' => 'Terme technique'
            ])
            ->add('slug', null, [
                'label' => 'Identifiant URL (Slug)'
            ])
            ->add('definition', null, [
                'label' => 'Définition'
            ])
            ->add('author', null, [
                'label' => 'Auteur du texte',
                'choice_label' => function ($user) {
                    return $user->getFirstName() . ' ' . $user->getLastName();
                }
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lexicon::class,
        ]);
    }

    public function __toString(): string
{
    // Si le prénom ou le nom sont vides, on affiche l'email par défaut
    // On utilise trim() pour enlever l'espace inutile si les champs sont vides
    $fullName = trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? ''));

    return $fullName !== '' ? $fullName : ($this->email ?? 'Utilisateur sans nom');
}
}
