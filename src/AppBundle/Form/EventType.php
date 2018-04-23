<?php

namespace AppBundle\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\AppBundle;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add(
                'start',
                DateTimeType::class,
                [
                    'widget'        => 'single_text',
                    'format'        => 'yyyy-MM-dd\'T\'HH:mm:ssZZZZZ',
                    'property_path' => 'releaseDate',
                ]
            )
            ->add(
                'end',
                DateTimeType::class,
                [
                    'widget'        => 'single_text',
                    'format'        => 'yyyy-MM-dd\'T\'HH:mm:ssZZZZZ',
                    'property_path' => 'releaseDate',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'         => Event::class,
                'allow_extra_fields' => true,
                'csrf_protection'    => false,
            ]
        );
    }
}
