<?php 

namespace Customize\Controller\Admin\Content\Inquiry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;   
use Customize\Repository\InquirySubCategoryRepository;

class InquirySubCategoryController extends AbstractController
{
    private $inquirySubCategoryRepository;

    public function __construct(
        InquirySubCategoryRepository $inquirySubCategoryRepository
    ) {
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
}
