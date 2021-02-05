<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'nom d\'utilisateur ',
                    'class' => 'form-control, textbox'
                ]
            ])

            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'adresse email',
                    'class' => 'form-control, textbox'
                ]
            ])

            ->add('password', PasswordType::class, [
                'attr' => [
                    'tooltip' => 'Le mot de passe doit faire 8 caractères',
                    'class' => 'form-control, textbox'
                ]
            ])

            ->add('confirm_password', PasswordType::class, [
                'attr' => [
                    'tooltip' => 'Le mot de passe doit correspondre avec le password',
                    'class' => 'form-control, textbox'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
