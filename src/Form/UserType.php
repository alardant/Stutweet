<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContext;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'label' => 'Nom d\'utilisateur',
                    'required' => true,
                    'constraints' => [
                        new Length(min: 2, max: 180, maxMessage: 'Le titre ne doit pas faire plus de 180 caractères', minMessage: 'Le titre doit faire moins de 2 caractères'),
                        new NotBlank(message: 'Le nom d\'utilisateur ne doit pas etre vide')
                    ]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'label' => 'Mot de passe',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(message: 'Le mot de passe ne doit ne doit pas etre vide')
                    ]
                ]
            )
            ->add(
                'confirm',
                PasswordType::class,
                [
                    'label' => 'Confirmer le mot de passe',
                    'required' => true,
                    'constraints' => [
                        new NotBlank(message: 'Le mot de passe ne doit ne doit pas etre vide'),
                        new Callback(
                            callback: function ($value, ExecutionContext $ec) {
                                if ($ec->getRoot()['password']->getViewData() !== $value) {
                                    $ec->addViolation('Les mots de passe doivent etre identitques');
                                }
                            }
                        )
                    ]
                ]
            );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_fieldname' => '_token',
            'csrf_token_id' => 'post_item'
        ]);
    }
}
