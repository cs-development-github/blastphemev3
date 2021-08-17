<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserAdminModifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class,[
                'label' => 'E-mail'
            ])
            ->add('roles', ChoiceType::class,[
                'label' => 'Choisir le role',
                'multiple' => true,
                'expanded' => true, // render check-boxes
                'choices' => [
                    'Apocryphe du dimanche' => 'ROLE_APOCRYPHE',
                    'Souilleur de Bénitier' => 'ROLE_SOUILLEUR',
                    'Grand Blastphémateur' => 'ROLE_ADMIN',
                    
                ],
            ])
            ->add('description', TextareaType::class,[
                'label' => 'Description du profil (visible dans A propos)'
            ])
            ->add('name', TextType::class,[
                'label' => 'Nom de l\'utilisateur (visible dans A propos)'
            ])
            ->add('photo', FileType::class,[
                'label'=>'Photo de profil du compte (visible dans A propos) ',
                'mapped' => false,
                'required' => false
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
