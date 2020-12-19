<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Název',
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'label' => 'Popis',
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                         'maxSize' => '10240k',
                         'mimeTypes' => [
                             'image/jpeg',
                             'image/png',
                         ],
                         'mimeTypesMessage' => 'Prosím nahrajte soubor typu JPEG, nebo PNG',
                     ])
                ],
            ])
            ->add('latitude', TextType::class, [
                'label' => 'Zeměpisná šířka'
            ])
            ->add('longitude', TextType::class, [
                'label' => 'Zeměpisná délka'
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
