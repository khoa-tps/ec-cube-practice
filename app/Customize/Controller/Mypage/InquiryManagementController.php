<?php

namespace Customize\Controller\Mypage;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eccube\Controller\AbstractController;
use Customize\Repository\InquiryRepository;
use Customize\Entity\Inquiry;
use Customize\Repository\InquiryCategoryRepository;
use Customize\Repository\InquirySubCategoryRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class InquiryManagementController extends AbstractController
{
    private $inquiryRepository;
    private $inquiryCategoryRepository;
    private $inquirySubCategoryRepository;

    public function __construct(InquiryRepository $inquiryRepository, InquiryCategoryRepository $inquiryCategoryRepository, InquirySubCategoryRepository $inquirySubCategoryRepository)
    {
        $this->inquiryRepository = $inquiryRepository;
        $this->inquiryCategoryRepository = $inquiryCategoryRepository;
        $this->inquirySubCategoryRepository = $inquirySubCategoryRepository;
    }
    /**
     * @Route("/mypage/inquiry", name="mypage_inquiry")
     * @Template("Mypage/inquiry_management.twig")
     */
    public function index()
    {
        $Inquiries = $this->inquiryRepository->createQueryBuilder('i')
            ->where('i.user_id = :user_id')
            ->setParameter('user_id', $this->getUser()->getId())
            ->orderBy('i.created_at', 'DESC')
            ->getQuery()
            ->getResult();

        return ['Inquiries' => $Inquiries];
    }

    /**
     * @Route("/mypage/inquiry/create", name="mypage_inquiry_create")
     * @Template("Mypage/inquiry_management_create.twig")
     */
    public function create(Request $request)
    {
        $inquiry_categories = $this->inquiryCategoryRepository->getAllParentCate();
        $builder = $this->formFactory->createBuilder(FormType::class, new Inquiry())
        ->add('title', TextType::class)
        ->add('detail', TextareaType::class)
        ->add('email', TextType::class, [
            'required' => true,
            'label' => 'Email',
            'data' => $this->getUser()->getEmail(), // ← Pre-fill email
        ]);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Inquiry = $form->getData();
            $Inquiry->setUserId($this->getUser()->getId());
            $Inquiry->setStatus(0);
            $Inquiry->setCreatedAt(new \DateTime());
            
            // Convert sub_category_id string to entity object
            $subCategoryId = $request->request->get('sub_inquiry_category');
            if ($subCategoryId) {
                $subCategory = $this->inquirySubCategoryRepository->find($subCategoryId);
                $Inquiry->setInquirySubCategory($subCategory);
            }
            
            $this->entityManager->persist($Inquiry);
            $this->entityManager->flush();
            
            return $this->redirectToRoute('mypage_inquiry');
        }
        return [
            'form' => $form->createView(),
            'inquiry_categories' => $inquiry_categories,
        ];
    }

    /**
     * @Route("/mypage/inquiry/get-child-categories", name="mypage_inquiry_get_child_categories")
     */
    public function getChildCategories(Request $request)
    {
        $categoryId = $request->request->get('category_id');
        $childCategories = $this->inquiryCategoryRepository->getChildCategories($categoryId);
        return $this->json([
            'child_categories' => $childCategories,
        ]);
    }

    /**
     * @Route("/mypage/inquiry/get-sub-categories", name="mypage_inquiry_get_sub_categories")
     */
    public function getSubCategories(Request $request)
    {
        $categoryId = $request->request->get('category_id');
        $subCategories = $this->inquirySubCategoryRepository->getSubCategories($categoryId);
        return $this->json([
            'sub_categories' => $subCategories,
        ]);
    }
}
