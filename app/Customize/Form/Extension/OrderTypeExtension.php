<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\Shopping\OrderType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user_note', TextareaType::class, [
            'required' => false,
            'label' => 'front.shopping.user_note',
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield OrderType::class;
    }
}
