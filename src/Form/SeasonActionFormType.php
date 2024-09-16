<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonActionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startsAt', DateType::class, [
                'label' => 'Starts on',
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'Ends on'
            ])
            ->add('races', NumberType::class, [])
            ->add('sprints', NumberType::class, [])
            ->add('completedRaces', NumberType::class, [])
            ->add('completedSprints', NumberType::class, [])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Season::class
        ]);
    }
}
