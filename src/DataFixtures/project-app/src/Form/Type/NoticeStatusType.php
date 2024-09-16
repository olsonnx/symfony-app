<?php

namespace App\Form\Type;

use App\Entity\NoticeStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoticeStatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('status', ChoiceType::class, [
            'choices' => array_combine(
                // Zamiast status->name, u¿ywamy po prostu wartoœci statusów
                array_map(fn ($status) => ucfirst($status), NoticeStatus::getAvailableStatuses()), // Etykiety statusów
                NoticeStatus::getAvailableStatuses() // Wartoœci statusów
            ),
            'label' => 'Status',
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Mo¿e byæ ustawione na 'null', jeœli przekazujemy tylko status
        ]);
    }
}
