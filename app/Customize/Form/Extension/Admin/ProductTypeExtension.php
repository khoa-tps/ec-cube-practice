<?php

namespace Customize\Form\Extension\Admin;

use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Admin\ProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Eccube\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Eccube\Entity\Category;


class ProductTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;
      /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    public function __construct(EccubeConfig $eccubeConfig, CategoryRepository $categoryRepository)
    {
        $this->eccubeConfig = $eccubeConfig;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('description_detail_english', TextareaType::class, [
            'required' => false,
            'purify_html' => true,
            'constraints' => [
                new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }
}
