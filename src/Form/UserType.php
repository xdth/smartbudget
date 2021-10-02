<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('roles', 
                  ChoiceType::class,
                  [
                    'required' => true,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => [
                      'User' => 'ROLE_USER',
                      'Admin' => 'ROLE_ADMIN'
                    ]
                  ])
            ->add('password')
            ->add('email')
            ->add('telephone')
            ->add('details')
        ;

        $builder->get('roles')
                ->addModelTransformer(new CallbackTransformer(
                  function($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                  },
                  
                  function($rolesString) {
                    return [$rolesString];
                  }
                ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
