<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TypeTextType::class, [
                'label' => 'pseudo',
                'attr' => [
                    'placeholder' => 'Saisissez votre speudo'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-Mail',
                'attr' => [
                    'placeholder' => 'Saisissez votre E-Mail'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'critique',
                'attr' => [
                    'placeholder' => 'dites ce que vous avez penser du film ou de la série'
                ]
            ])
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1
                ],
                'placeholder' => 'Votre appréciation:',
                // si on veut masquer le lable vu qu on a un placeholder
                'label' => false
            ])
            ->add('reactions', ChoiceType::class, [
                'label' => 'Ce film vous a fait:',
                'choices' => [
                    'Rire' => 'smile',
                    'Pleurer' => 'cry',
                    'Réfléchir' => 'think',
                    'Dormir' => 'sleep',
                    'Rêver' => 'dream'
                ],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('watchedAt', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Vous avez vu ce film ou série le:',
                'input' => 'datetime_immutable',
                'format' => 'yyyy-MM-dd'

            ])
            //->add('movie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
