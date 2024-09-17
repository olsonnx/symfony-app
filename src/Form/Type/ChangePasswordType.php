<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Change Password type.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'label.current_password',
                'mapped' => false,
                'constraints' => [
                    new UserPassword(['message' => 'validators.invalid_current_password']),
                ],
            ])
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
     * Configure the form options.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        // No entity class is mapped
    }

    /**
     * Get the block prefix.
     *
     * @return string The block prefix
     */
    public function getBlockPrefix(): string
    {
        return 'change_password';
    }
}
