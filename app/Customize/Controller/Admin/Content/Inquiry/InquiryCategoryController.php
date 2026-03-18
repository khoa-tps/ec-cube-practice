<?php

namespace Customize\Controller\Admin\Content\Inquiry;

use Eccube\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Customize\Entity\InquiryCategory;
use Customize\Repository\InquiryCategoryRepository;


class InquiryCategoryController extends AbstractController
{
    /**
     * @var InquiryCategoryRepository
     */
    private $inquiryCategoryRepository;
    
    public function __construct(InquiryCategoryRepository $inquiryCategoryRepository)
    {
        $this->inquiryCategoryRepository = $inquiryCategoryRepository;
    }
    
    /**
     * @Route("/%eccube_admin_route%/content/inquiry_category", name="admin_content_inquiry_category")
     * @Route("/%eccube_admin_route%/content/inquiry_category/{id}", name="admin_content_inquiry_category_detail", requirements={"id" = "\d+"})
     * @Template("@admin/Content/Inquiry/inquiry_category.twig")
     */
    public function index(Request $request)
    {
        $inquiryCategories = $this->inquiryCategoryRepository->getList();
        
        // Prepare parent names for each category
        $categoriesWithParentNames = [];
        foreach ($inquiryCategories as $category) {
            $parentName = '';
            if ($category->getParentId()) {
                $parentName = $this->inquiryCategoryRepository->getParentName($category->getParentId());
            }
            $categoriesWithParentNames[] = [
                'category' => $category,
                'parentName' => $parentName
            ];
        }
        
        return [
            'inquiryCategories' => $categoriesWithParentNames,
            'menus' => ['inquiry_management', 'inquiry_category'],
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_category/create", name="admin_content_inquiry_category_create")
     * @Route("/%eccube_admin_route%/content/inquiry_category/{id}/edit", name="admin_content_inquiry_category_edit", requirements={"id" = "\d+"})
     * @Template("@admin/Content/Inquiry/inquiry_category_create.twig")
     */
    public function create(Request $request, $id = null)
    {
        if(is_null($id)){
            // Create new inquiry category
            $inquiryCategory = new InquiryCategory();
        } else {
            // Edit inquiry category
            $inquiryCategory = $this->inquiryCategoryRepository->find($id);
        }
        $builder = $this->formFactory->createBuilder(FormType::class, $inquiryCategory)
        ->add('name', TextType::class)
        ->add('parent_id', EntityType::class, [
            'placeholder' => '選択してください',
            'data' => $inquiryCategory->getParentId() ? $this->inquiryCategoryRepository->find($inquiryCategory->getParentId()) : null,
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
        
        if($form->isSubmitted() && $form->isValid()){
            $parentId = $form->get('parent_id')->getData() ?? 0;
            if($parentId){
                $parentInquiryCategory = $this->inquiryCategoryRepository->find($parentId);
                $inquiryCategory->setParentId($parentInquiryCategory->getId());
            }
            $inquiryCategory->setCreatedAt(new \DateTime());
            $inquiryCategory->setUpdatedAt(new \DateTime());
            $this->entityManager->persist($inquiryCategory);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_content_inquiry_category');
        }
        
        return [
            'form' => $form->createView(),
            'menus' => ['inquiry_management', 'inquiry_category'],
        ];
    }

    /**
     * Delete a inquiry category.
     * @Route("/%eccube_admin_route%/content/inquiry_category/{id}/delete", name="admin_content_inquiry_category_delete", methods={"DELETE"})
     * @param Request $request
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $this->isTokenValid();
        $inquiryCategory = $this->inquiryCategoryRepository->find($id);
        $success = $inquiryCategory ? true : false;
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => $success,
                'message' => $success ? 'admin.common.delete_complete' : 'admin.common.delete_error',
            ]);
        }
        if (!$inquiryCategory) {
            $this->deleteMessage();
            return [
                'success' => false,
                'message' => 'admin.common.delete_error',
            ];
        }
        $this->entityManager->remove($inquiryCategory);
        $this->entityManager->flush();
        $this->addSuccess('admin.common.delete_complete', 'admin');
        return $this->redirectToRoute('admin_content_inquiry_category');
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_category/sort_no_move", name="admin_content_inquiry_category_sort_no_move", methods={"POST"})
     */
    public function sortNoMove(Request $request)
    {
        $this->isTokenValid();
        $sortNos = $request->request->all();
        foreach ($sortNos as $id => $sortNo) {
            $inquiryCategory = $this->inquiryCategoryRepository->find($id);
            $inquiryCategory->setSortNo($sortNo);
            $this->entityManager->persist($inquiryCategory);
        }
        $this->entityManager->flush();
        return $this->json([
            'success' => true,
            'message' => 'admin.common.update_complete',
        ]);
    }
}
