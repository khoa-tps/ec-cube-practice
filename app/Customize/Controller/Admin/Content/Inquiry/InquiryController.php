<?php

namespace Customize\Controller\Admin\Content\Inquiry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Customize\Repository\InquiryRepository;

class InquiryController extends AbstractController
{
    private InquiryRepository $inquiryRepository;

    public function __construct(
        InquiryRepository $inquiryRepository,
    )
    {
        $this->inquiryRepository = $inquiryRepository;
    }
    
    /**
     * @Route("/%eccube_admin_route%/content/inquiry_list", name="admin_content_inquiry_list")
     * @Template("@admin/Content/Inquiry/inquiry_list.twig")
     */
    public function index()
    {
        $inquiries = $this->inquiryRepository->findAll();
        return [
            'inquiries' => $inquiries,
        ];
    }
}
