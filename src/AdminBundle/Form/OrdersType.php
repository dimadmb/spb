<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use AdminBundle\Form\Type\RouteType;






class OrdersType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('id', TextType::class , ['required'=> false])
			->add('name', TextType::class , ['required'=> false])
			->add('route', RouteType::class , ['required'=> true])
			->add('date', DateType::class , ['widget' => 'single_text','required'=> false])
			->add('submit', SubmitType::class,array('label' => 'Фильтровать'))
		;
    }


/*
    public function configureOptions(OptionsResolver $resolver)
    {

    }	
*/
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }	
	
}