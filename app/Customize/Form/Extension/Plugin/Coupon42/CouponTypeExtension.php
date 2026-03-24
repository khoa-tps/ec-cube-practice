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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Plugin\Coupon42\Entity\Coupon;


class CouponTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $coupon = $event->getData();
            if (!$coupon || null === $coupon->getId()) {
                // Set defaults for NEW coupon only
                if ($coupon instanceof \Plugin\Coupon42\Entity\Coupon) {
                    if (null === $coupon->getIssueType()) {
                        $coupon->setIssueType(2); // Issued as needed
                    }
                    if (null === $coupon->getCouponType()) {
                        $coupon->setCouponType(Coupon::ALL);
                    }
                    if (null === $coupon->getCouponMember()) {
                        $coupon->setCouponMember(true);
                    }
                    if (null === $coupon->getAvailableFromDate()) {
                        $coupon->setAvailableFromDate(new \DateTime());
                    }
                    if (null === $coupon->getAvailableToDate()) {
                        $coupon->setAvailableToDate((new \DateTime())->modify('+10 years'));
                    }
                    if (null === $coupon->getCouponRelease()) {
                        $coupon->setCouponRelease(1000000);
                    }
                }
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            if (!isset($data['coupon_type'])) {
                $data['coupon_type'] = Coupon::ALL;
            }
            if (!isset($data['coupon_member'])) {
                $data['coupon_member'] = 1;
            }
            if (!isset($data['available_from_date']) || empty($data['available_from_date'])) {
                $data['available_from_date'] = (new \DateTime())->format('Y-m-d');
            }
            if (!isset($data['available_to_date']) || empty($data['available_to_date'])) {
                $data['available_to_date'] = (new \DateTime())->modify('+10 years')->format('Y-m-d');
            }
            if (!isset($data['coupon_release']) || empty($data['coupon_release'])) {
                $data['coupon_release'] = 1000000;
            }
            $event->setData($data);
        });

        $builder->add('issue_type', ChoiceType::class, [
            'label' => 'plugin_coupon.admin.label.issue_type',
            'required' => true,
            'expanded' => true,
            'multiple' => false,
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
        ]);
        $builder->add('target_users', ChoiceType::class, [
            'label' => 'Issuance trigger',
            'required' => false,
            'expanded' => true,
            'multiple' => false,
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
        ->add('coupon_cd', TextType::class, [
            'label' => 'Coupon code',
            'required' => false,
        ])
        ->add('discount_price', IntegerType::class, [
            'label' => 'Discount price',
            'required' => false,
        ])
        ->add('discount_rate', IntegerType::class, [
            'label' => 'Discount rate',
            'required' => false,
        ])
        ->add('available_from_date', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'error_bubbling' => true,
        ])
        ->add('available_to_date', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
            'error_bubbling' => true,
        ])
        ->add('coupon_release', IntegerType::class, [
            'required' => false,
            'error_bubbling' => true,
        ])
        ->add('coupon_type', ChoiceType::class, [
            'required' => false,
            'choices' => array_flip([
                1 => 'Product',
                2 => 'Category',
                3 => 'All',
            ]),
            'error_bubbling' => true,
        ])
        ->add('coupon_member', ChoiceType::class, [
            'required' => false,
            'choices' => array_flip([
                1 => 'Yes',
                0 => 'No',
            ]),
            'error_bubbling' => true,
        ])
        ->add('issuance_trigger', ChoiceType::class, [
            'label' => 'Issuance trigger',
            'required' => false,
            'expanded' => true,
            'multiple' => false,
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
        ])
        ->add('issuance_period_to', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
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
        ])
        ->add('term_usage_period', IntegerType::class, [
            'label' => 'Usage period',
            'required' => false,
        ])
        ->add('term_usage_period_from', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
        ])
        ->add('term_usage_period_to', DateType::class, [
            'label' => 'Date and time of issuance reservation',
            'required' => false,
            'widget' => 'single_text',
            'input' => 'datetime',
        ])
        ->add('term_available_count', IntegerType::class, [
            'label' => 'Number of uses',
            'required' => false,
        ])
        ->add('term_available_cycle_cycle', ChoiceType::class, [
            'required' => false,
            'multiple' => false,
            'choices' => array_flip([
                1 => 'Day',
                2 => 'Week',
                3 => 'Month'
            ])
        ])
        ->add('term_available_cycle_count', IntegerType::class, [
            'label' => 'Number of uses',
            'required' => false,
        ])
        ->add('term_minimun_spend_amount', IntegerType::class, [
            'label' => 'Minimum spend amount',
            'required' => false,
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
        ])
        ->add('term_coupon_available_unlimited', CheckboxType::class, [
            'label' => 'Indefinite',
            'required' => false,
        ])
        ->add('acquisition_conditions', TextType::class, [
            'label' => 'Acquisition conditions',
            'required' => false,
        ])
        ->add('detail_link', TextType::class, [
            'label' => 'Detail link',
            'required' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [CouponType::class];
    }
}