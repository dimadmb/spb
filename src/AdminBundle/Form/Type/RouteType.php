<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RouteType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'Все' => 0,
                'СПб - Петергоф - СПб' => 1,
                'СПб - Петергоф' => 2,
                'Петергоф - СПб' => 3,
                
            )
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}