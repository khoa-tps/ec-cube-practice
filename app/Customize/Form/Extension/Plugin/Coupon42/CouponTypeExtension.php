<?php

namespace Customize\Form\Extension\Plugin\Coupon42;

use Plugin\Coupon42\Form\Type\CouponType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Customize\Config\CouponConfig;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Eccube\Entity\Customer;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class CouponTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('issue_type', ChoiceType::class, [
            'label' => 'plugin_coupon.admin.label.issue_type',
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
            'choices' => array_flip([
                1 => 'Bulk Issue',
                2 => 'Issued as needed',
                3 => 'Code Issuance'
            ]),
        ]);
        $builder->add('issue_type_from', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ]);
        $builder->add('target_users', ChoiceType::class, [
            'label' => 'plugin_coupon.admin.label.target_users',
            'required' => false,
            'choices' => [
                'All' => CouponConfig::TARGET_USERS_ALL,
                'New Users' => CouponConfig::TARGET_USERS_NEW,
                'Birthday Users' => CouponConfig::TARGET_USERS_BIRTHDAY,
                'Specific Users' => CouponConfig::TARGET_SPECIFIC_USERS
            ],
            'expanded' => true,
            'multiple' => true,
        ])
        ->add('customer', EntityType::class, [
            'class' => Customer::class,
            'choice_label' => function ($Customer) {
                return $Customer->getName01() . ' ' . $Customer->getName02() . ' (' . $Customer->getEmail() . ')';
            },
            'placeholder' => '---',
            'label' => 'plugin_coupon.admin.label.customer',
            'required' => false,
            'multiple' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [CouponType::class];
    }
}