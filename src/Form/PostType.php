<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Post;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre',
                    'required' => false,
                    'constraints' => new Length(min: 2, max: 150, maxMessage: 'Le titre ne doit pas faire plus de 150 caractères', minMessage: 'Le titre doit faire moins de 0 caractères')
                ]
            )
            ->add(
                'content',
                TextareaType::class,
                [
                    'label' => 'Contenu',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(message: 'Le contenu ne doit pas être vide'),
                        new Length(min: 5, max: 320, maxMessage: 'Le titre ne doit pas faire plus de 320 caractères', minMessage: 'Le titre doit faire moins de 5 caractères')
                    ]
                ]
            )
            ->add(
                'image',
                FileType::class,
                [
                    'label' => 'Télécharger une image',
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '5m',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/gif',
                                'image/svg',
                                'image/png',
                                'image/webp',
                                'image/jpg'
                            ],
                            'mimeTypesMessage' => 'Veuillez proposer une image valide'
                        ])
                    ]
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'csrf_protection' => true,
            'csrf_fieldname' => '_token',
            'csrf_token_id' => 'post_item'
        ]);
    }
}
