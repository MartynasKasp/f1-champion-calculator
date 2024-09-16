<?php

namespace App\Form;

use App\Entity\Circuit;
use App\Entity\Race;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceActionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Held on',
            ])
            ->add('completed', CheckboxType::class, [
                'required' => false,
            ])
            ->add('canceled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('fullDistance', CheckboxType::class, [
                'required' => false,
            ])
            ->add('sprintRace', CheckboxType::class, [
                'required' => false,
            ])
            ->add('season', EntityType::class, [
                'class' => Season::class,
                'choice_label' => 'id'
            ])
            ->add('grandPrix', TextType::class, [])
            ->add('circuit', EntityType::class, [
                'class' => Circuit::class,
                'choice_label' => 'circuit',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Race::class
        ]);
    }
}
