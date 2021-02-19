<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Component\Validator\Constraints\NotNull;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pokemon', null, [
              'label' => 'Composez votre équipe (jusqu\'à 6 Pokémon ou 5 pour la suggestion de Pokémon)',
              'expanded' => true,
              'multiple' => true,
              'constraints' => [
                new NotNull(['message' => 'Please choose at least one pokemon']),
                new Count(['min' => 1,
                           'max' => 6,
                           'minMessage' => 'Please choose at least one pokemon',
                           'maxMessage' => 'A team cannot have more than 6 pokemon'])
              ]
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
