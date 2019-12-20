<?php

namespace App\Form\Type;

use App\Document\Coordinates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CoordinatesType
 *
 * @package App\Form\Type
 */
class CoordinatesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('latitude', TextType::class, [
                "error_bubbling" => true
            ])
            ->add('longitude', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Coordinates::class,
        ]);
    }
}