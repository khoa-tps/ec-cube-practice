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
     * @Template("@admin/Content/Inquiry/inquiry_sub_category.twig")
     */
    public function index()
    {
        $inquirySubCategory = $this->inquirySubCategoryRepository->findBy([], ['sort_no' => 'ASC']);
        
        // Prepare parent names for each category
        $categoriesWithParentNames = [];
        foreach ($inquirySubCategory as $category) {
            $parentName = '';
            if ($category->getCategoryId()) {
                $parentName = $this->inquiryCategoryRepository->find($category->getCategoryId())->getName();
            }
            $categoriesWithParentNames[] = [
                'subCategory' => $category,
                'parentName' => $parentName
            ];
        }
        return [
            'inquirySubCategory' => $categoriesWithParentNames,
            'menus' => ['inquiry_management', 'inquiry_sub_category'],
        ];   
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/delete/{id}", name="admin_content_inquiry_sub_category_delete", requirements={"id" = "\d+"})
     */
    public function delete(Request $request, $id)
    {
        $inquirySubCategory = $this->inquirySubCategoryRepository->find($id);
        $this->entityManager->remove($inquirySubCategory);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_content_inquiry_sub_category');
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/create", name="admin_content_inquiry_sub_category_create")
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/edit/{id}", name="admin_content_inquiry_sub_category_edit", requirements={"id" = "\d+"})
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
            'query_builder' => function($repository) {
                return $repository->createQueryBuilder('ic')
                    ->where('ic.parent_id > 0')
                    ->orderBy('ic.name', 'ASC');
            },
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
            'form' => $form->createView(),
            'menus' => ['inquiry_management', 'inquiry_sub_category'],
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_sub_category/sort_no_move", name="admin_content_inquiry_sub_category_sort_no_move", methods={"POST"})
     */
    public function sortNoMove(Request $request)
    {
        $sortNos = $request->request->all();
        foreach ($sortNos as $id => $sortNo) {
            $inquirySubCategory = $this->inquirySubCategoryRepository->find($id);
            $inquirySubCategory->setSortNo($sortNo);
            $this->entityManager->persist($inquirySubCategory);
        }
        $this->entityManager->flush();
        return $this->json(['status' => 'success']);
    }
}

