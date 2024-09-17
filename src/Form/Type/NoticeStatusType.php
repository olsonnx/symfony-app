<?php
/**
 * Notice management app.
 *
 * contact me at aleksander.ruszkowski@student.uj.edu.pl
 */

namespace App\Form\Type;

use App\Entity\NoticeStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Notice Status type.
 */
class NoticeStatusType extends AbstractType
{
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options for the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('status', ChoiceType::class, [
            'choices' => array_combine(
                array_map(fn ($status) => ucfirst($status), NoticeStatus::getAvailableStatuses()), // Etykiety statusów
                NoticeStatus::getAvailableStatuses() // Wartości statusów
            ),
            'label' => 'Status',
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    /**
     * Configure form options.
     *
     * @param OptionsResolver $resolver The resolver for options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Ustaw na 'null', jeśli przekazujemy tylko status
        ]);
    }
}
