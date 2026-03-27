<?php
namespace Customize\Form\Extension;

use Eccube\Form\Type\SearchProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;
use Customize\Entity\ProductFeature;

class SearchProductTypeExtension extends AbstractTypeExtension
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $featureRepository = $this->entityManager->getRepository(ProductFeature::class);
        $features = $featureRepository->findBy(['status' => 1]);
        $choices = [];
        foreach ($features as $feature) {
            $choices[$feature->getFeatureName()] = $feature->getId();
        }
        $builder->add('product_feature_id', ChoiceType::class, [
            'required' => false,
            'choices' => $choices,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield SearchProductType::class;
    }
}