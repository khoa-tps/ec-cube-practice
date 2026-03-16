<?php 

namespace Customize\Controller\Admin\Content\Inquiry;

use Eccube\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;   
use Customize\Repository\InquirySubCategoryRepository;
use Customize\Repository\InquiryCategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Customize\Entity\InquirySubCategory;
use Customize\Entity\InquiryCategory;

class InquirySubCategoryController extends AbstractController
{
    private $inquirySubCategoryRepository;
    private $inquiryCategoryRepository;

    public function __construct(
        InquirySubCategoryRepository $inquirySubCategoryRepository,
        InquiryCategoryRepository $inquiryCategoryRepository
    ) {
        $this->inquiryCategoryRepository = $inquiryCategoryRepository;
        $this->inquirySubCategoryRepository = $inquirySubCategoryRepository;
    }
    
    /**
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category", name="admin_content_inquiry_sub_category")
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/{id}", name="admin_content_inquiry_sub_category_edit", requirements={"id" = "\d+"})
     * @Template("@admin/Content/Inquiry/inquiry_sub_category.twig")
     */
    public function index()
    {
        $inquirySubCategory = $this->inquirySubCategoryRepository->findAll();

        return [
            'inquirySubCategory' => $inquirySubCategory
        ];   
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/create", name="admin_content_inquiry_sub_category_create")
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/create/{id}", name="admin_content_inquiry_sub_category_create_edit", requirements={"id" = "\d+"})
     * @Template("@admin/Content/Inquiry/inquiry_sub_category_create.twig")
     */
    public function create(Request $request, $id = null)
    {
        if(is_null($id)){
            $inquirySubCategory = new InquirySubCategory();
        }else{
            $inquirySubCategory = $this->inquirySubCategoryRepository->find($id);
        }

        $builder = $this->formFactory->createBuilder(FormType::class, $inquirySubCategory)
        ->add('name', TextType::class)
        ->add('category_id', EntityType::class, [
            'placeholder' => '選択してください',
            'data' => $inquirySubCategory->getCategoryId() ? $this->inquiryCategoryRepository->find($inquirySubCategory->getCategoryId()) : null,
            'class' => InquiryCategory::class,
            'choice_label' => 'name',
            'required' => false,
        ])
        ->add('sort_no', IntegerType::class)
           ->add('created_at', HiddenType::class, [
            'mapped' => false,
        ])
        ->add('updated_at', HiddenType::class, [
            'mapped' => false,
        ])
        ->add('deleted_at', HiddenType::class, [
            'mapped' => false,
        ]);
        $form = $builder->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryId = $form->get('category_id')->getData() ?? 0;
            if($categoryId){
                $category = $this->inquiryCategoryRepository->find($categoryId);
                $inquirySubCategory->setCategoryId($category->getId());
            }
            $inquirySubCategory->setCreatedAt(new \DateTime());
            $inquirySubCategory->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($inquirySubCategory);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_content_inquiry_sub_category');
        }
        
        return [
            'form' => $form->createView()
        ];
    }
}
