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
     * @Template("@admin/Product/ProductFeature/index.twig")
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
            'label' => '特集名',
        ])
        ->add('created_at', DateType::class, [
            'label' => '公開開始日時',
        ])
        ->add('publish_date_to', DateType::class, [
            'label' => '公開終了日時',
        ])
        ->add('submit', SubmitType::class, [
            'label' => '保存',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->productFeatureRepository->save($feature);
            return $this->redirectToRoute('admin_product_feature');
        }
        return [
            'form' => $form->createView(),
        ];
    }
}