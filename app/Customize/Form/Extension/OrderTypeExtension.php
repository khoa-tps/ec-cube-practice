<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\Shopping\OrderType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;

class OrderTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
           if (!$options['skip_add_form']) {
            $builder->add('user_note', TextareaType::class, [
                'required' => false,
                'label' => 'front.shopping.user_note',
                'constraints' => [
                    new Length(['max' => 4000]),
                ],
            ]);
        }
    }

    public static function getExtendedTypes(): iterable
    {
        yield OrderType::class;
    }
}
