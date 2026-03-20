<?php
namespace Customize\Controller\Admin\Product;

use Eccube\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Customize\Repository\ProductFeatureRepository;
use Customize\Entity\ProductFeature;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductFeatureController extends AbstractController
{
    /**
     * @var ProductFeatureRepository
     */
    protected $productFeatureRepository;
    public function __construct(
        ProductFeatureRepository $productFeatureRepository
    ) {
        $this->productFeatureRepository = $productFeatureRepository;
    }
    /**
    * @Route("/%eccube_admin_route%/product/feature", name="admin_product_feature", methods={"GET", "POST"})
    * @Template("@admin/Product/ProductFeature/index.twig")
    */
    public function index(Request $request)
    {
        $features = $this->productFeatureRepository->findAll();
        return [
            'features' => $features,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/feature/create", name="admin_product_feature_create", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/product/feature/{id}", name="admin_product_feature_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductFeature/create_edit.twig")
     */
    public function edit(Request $request, $id = null)
    {
        if(is_null($id)){
            $feature = new ProductFeature();
        }else {
            $feature = $this->productFeatureRepository->find($id);
            if(is_null($feature)){
                throw new \Exception('Feature not found');
            }
        }
        $form = $this->createForm(FormType::class, $feature)
        ->add('feature_name', TextType::class, [
            'label' => 'Feature Name',
        ])
        ->add('status',  ChoiceType::class, [
            'label' => 'Status',
            'choices' => [
                'Active' => 1,
                'Inactive' => 0,
            ],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feature->setCreatedAt(new \DateTime());
            $feature->setUpdatedAt(new \DateTime());
            $this->productFeatureRepository->save($feature);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_product_feature');
        }
        return [
            'form' => $form->createView(),
            'feature' => $feature,
        ];
    }
}