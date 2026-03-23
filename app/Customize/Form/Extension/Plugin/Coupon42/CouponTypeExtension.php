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
use Customize\Entity\Shop;
use Eccube\Entity\Product;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Eccube\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


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
            'data' => 2,
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
            'label' => 'Issuance trigger',
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
            'choices' => [
                'All' => CouponConfig::TARGET_USERS_ALL,
                'New Users' => CouponConfig::TARGET_USERS_NEW,
                'Birthday Users' => CouponConfig::TARGET_USERS_BIRTHDAY,
                'Specific Users' => CouponConfig::TARGET_SPECIFIC_USERS
            ]
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
        ])
        ->add('shop_id', EntityType::class, [
            'class' => Shop::class,
            'choice_label' => function ($Shop) {
                return $Shop->getName();
            },
            'placeholder' => '---',
            'label' => 'plugin_coupon.admin.label.shop',
            'required' => false,
        ])
        ->add('product_id', EntityType::class, [
            'class' => Product::class,
            'choice_label' => function ($Product) {
                return $Product->getName();
            },
            'placeholder' => '---',
            'label' => 'Products eligible for issuance',
            'required' => false,
        ])
        ->add('email_notification_content', TextType::class, [
            'label' => 'Inserted text',
            'required' => false,
        ])
        ->add('issuance_trigger', ChoiceType::class, [
            'label' => 'Issuance trigger',
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
            'choices' => array_flip([
                CouponConfig::TARGET_USERS_NEW => 'New Users',
                CouponConfig::TARGET_USERS_PURCHASE => 'Purchase',
                CouponConfig::TARGET_REVIEW => 'Review Submission'
            ])
        ])
        ->add('issuance_period_from', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('issuance_period_to', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('issuance_shop_id', EntityType::class, [
            'class' => Shop::class,
            'choice_label' => function ($Shop) {
                return $Shop->getName();
            },
            'placeholder' => '---',
            'label' => 'plugin_coupon.admin.label.shop',
            'required' => false,
        ])
        ->add('issuance_product_id', EntityType::class, [
            'class' => Product::class,
            'choice_label' => function ($Product) {
                return $Product->getName();
            },
            'placeholder' => '---',
            'label' => 'Products eligible for issuance',
            'required' => false,
        ])
        ->add('issuance_category_id', EntityType::class, [
            'class' => Category::class,
            'choice_label' => function ($Category) {
                return $Category->getName();
            },
            'placeholder' => '---',
            'label' => 'Categories eligible for issuance',
            'required' => false,
        ])
        ->add('issuance_display', ChoiceType::class, [
            'label' => 'Display on the coupon list page',
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
            'choices' => array_flip([
                1 => 'Yes',
                0 => 'No'
            ])
        ])
        ->add('issuance_quantity', IntegerType::class, [
            'label' => 'Number of Issues',
            'required' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_usage_period', IntegerType::class, [
            'label' => 'Usage period',
            'required' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_usage_period_from', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_usage_period_to', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_available_count', IntegerType::class, [
            'label' => 'Number of uses',
            'required' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_available_cycle_cycle', ChoiceType::class, [
            'required' => true,
            'expanded' => true,
            'multiple' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
            'choices' => array_flip([
                1 => 'Day',
                2 => 'Week',
                3 => 'Month'
            ])
        ])
        ->add('term_available_cycle_count', IntegerType::class, [
            'label' => 'Number of uses',
            'required' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_minimun_spend_amount', IntegerType::class, [
            'label' => 'Minimum spend amount',
            'required' => false,
            'constraints' => [
                new Assert\NotBlank(),
            ],
        ])
        ->add('term_shop_id', EntityType::class, [
            'class' => Shop::class,
            'choice_label' => function ($Shop) {
                return $Shop->getName();
            },
            'placeholder' => '---',
            'label' => 'plugin_coupon.admin.label.shop',
            'required' => false,
        ])
        ->add('term_category_id', EntityType::class, [
            'class' => Category::class,
            'choice_label' => function ($Category) {
                return $Category->getName();
            },
            'placeholder' => '---',
            'label' => 'Categories eligible for issuance',
            'required' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [CouponType::class];
    }
}