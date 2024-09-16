<?php

namespace App\Form\Type;

use App\Entity\NoticeStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 */
class NoticeStatusType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('status', ChoiceType::class, [
            'choices' => array_combine(
            // Zamiast status->name, używamy po prostu wartości statusów
                array_map(fn($status) => ucfirst($status), NoticeStatus::getAvailableStatuses()), // Etykiety statusów
                NoticeStatus::getAvailableStatuses() // Wartości statusów
            ),
            'label' => 'Status',
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Może być ustawione na 'null', jeśli przekazujemy tylko status
        ]);
    }
}
