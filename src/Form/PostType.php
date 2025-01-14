<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;

class PostType extends AbstractType
{
    /**
     * Builds the new post form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Název',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Popis',
                'attr' => [
                    'rows' => 5,
                ],
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Fotka',
                'mapped' => false,
                'constraints' => [
                    new NotNull([
                        'message' => 'Tato hodnota nesmí být prázdná.'
                    ]),
                    new Image([
                        'maxSize' => '2M',
                    ])
                ],
                'attr' => [
                    'accept' => 'image/*'
                ]
            ])
            ->add('latitude', NumberType::class, [
                'html5' => true,
                'label' => 'Zeměpisná šířka',
                'scale' => 6,
                'attr' => [
                    'step' => 0.0000001
                ]
            ])
            ->add('longitude', NumberType::class, [
                'html5' => true,
                'label' => 'Zeměpisná délka',
                'scale' => 6,
                'attr' => [
                    'step' => 0.0000001
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
