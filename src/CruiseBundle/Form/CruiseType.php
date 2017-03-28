<?php

namespace CruiseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use Symfony\Component\Form\FormInterface;

use CruiseBundle\Entity\Cruise;
use CruiseBundle\Entity\Price;


class CruiseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
		$builder
			->add('date',null,['widget'=>'single_text','html5' => false, 'label'=> 'Дата отправления' ])
			->add('time',null,['widget'=>'single_text','html5' => false, 'label'=> 'Время отправления'])
			->add('comment',null,['label'=>'Комментарий к рейсу'])
			->add('quantity',null, ['label'=> 'Количество мест для продажи'] )
			->add('direction',null, ['label' => 'Направление','required'  => true])  
		;
		
		/*
        $builder->add('prices', CollectionType::class, array(
            'entry_type' => PriceType::class
        ));
		*/
	
		$builder->add('prices', CollectionType::class, array(
            'entry_type' => \CruiseBundle\Form\PriceType::class, 
			//'data' => $cruise->getPrices(),
			
			'allow_add' => true,
			'allow_extra_fields' => true,
			'allow_delete' => true,
			'prototype' => true,
			'prototype_name' => 'collection', 
			'attr' => array(
					'class' => 'prices',
			),
			'by_reference' => false,
			
        ));	

		//$builder->add('submit', SubmitType::class); 
		
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CruiseBundle\Entity\Cruise'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'cruisebundle_cruise';
    }


}
