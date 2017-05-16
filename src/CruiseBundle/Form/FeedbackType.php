<?php

namespace CruiseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FeedbackType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add('subject', TextType::class , ['label'=>'Тема письма','required'=>false])
			->add('name', TextType::class , ['label'=>'Представьтесь','required'=>false])
			->add('email', EmailType::class , ['label'=>'Электронная почта','required'=>true])
			->add('body', TextareaType::class , ['label'=>'Сообщение','required'=>false])  
			->add('submit', SubmitType::class,array('label' => 'Отправить'))
		;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }


}
