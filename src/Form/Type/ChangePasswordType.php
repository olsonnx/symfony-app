<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Change Password type
 */
class ChangePasswordType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Pole na bieĹĽÄ…ce hasĹ‚o
            ->add('currentPassword', PasswordType::class, [
                'label' => 'label.current_password',
                'mapped' => false,
                'constraints' => [
                    new UserPassword(['message' => 'validators.invalid_current_password']),
                ],
            ])
            // Pole na nowe hasĹ‚o, z dwukrotnym powtĂłrzeniem dla walidacji
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'label.new_password'],
                'second_options' => ['label' => 'label.repeat_password'],
                'invalid_message' => 'validators.passwords_must_match',
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'validators.password_not_blank']),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'validators.password_min_length',
                    ]),
                ],
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Bez przypisania ĹĽadnej klasy encji
    }

    /**
     * block prefix getter
     *
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'change_password';
    }
}
