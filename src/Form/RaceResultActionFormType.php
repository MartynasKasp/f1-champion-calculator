<?php

namespace App\Form;

use App\Entity\Driver;
use App\Entity\RaceResult;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceResultActionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('position', NumberType::class, [
                'required' => false,
                'disabled' => $options['positionDisabled'],
            ])
            ->add('driver', EntityType::class, [
                'class' => Driver::class,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.number', 'ASC');
                },
                'choice_label' => function (Driver $driver): string {
                    return sprintf("#%s - %s", $driver->getNumber(), $driver->getFullName());
                },
            ])
            ->add('points', NumberType::class, [
                'required' => false,
                'empty_data' => 0
            ])
            ->add('resultStatus', TextType::class, [
                'required' => false,
                'empty_data' => 'Finished'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Save'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RaceResult::class,
            'positionDisabled' => true
        ]);
    }
}
