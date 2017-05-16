<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;






class DaysType extends AbstractType
{


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('dateStart', DateType::class , ['label'=>'Дата от','widget' => 'single_text','required'=> false])
			->add('dateEnd', DateType::class , ['label'=>'Дата по','widget' => 'single_text','required'=> false])
			->add('submit', SubmitType::class,array('label' => 'Фильтровать'))
		;
    }



    public function configureOptions(OptionsResolver $resolver)
    {
        /*$resolver->setDefaults(array(
            'choices' => array(
                'm' => 'Male',
                'f' => 'Female',
            )
        ));*/
    }	

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }	
	
}