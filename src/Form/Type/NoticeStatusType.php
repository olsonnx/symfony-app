<?php

namespace App\Form\Type;

use App\Entity\Enum\NoticeStatus;
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
                array_map(fn($status) => $status->name, NoticeStatus::cases()),
                NoticeStatus::cases()
            ),
            'label' => 'Status',
            'expanded' => false,
            'multiple' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null, // Możemy tutaj ustawić, aby przekazywał tylko status
        ]);
    }
}