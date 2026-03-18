<?php

namespace Customize\Controller\Admin\Content\Inquiry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Customize\Repository\InquiryRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class InquiryController extends AbstractController
{
    private InquiryRepository $inquiryRepository;
    protected EntityManagerInterface $entityManager;

    public function __construct(
        InquiryRepository $inquiryRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->inquiryRepository = $inquiryRepository;
        $this->entityManager = $entityManager;
    }
    
    /**
     * @Route("/%eccube_admin_route%/content/inquiry_list", name="admin_content_inquiry_list")
     * @Template("@admin/Content/Inquiry/inquiry_list.twig")
     */
    public function index()
    {
        $inquiries = $this->inquiryRepository->findAll(['status' => 'ASC']);
        return [
            'inquiries' => $inquiries,
            'menus' => ['inquiry_management', 'inquiry'],
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/content/inquiry_update_status", name="admin_content_inquiry_update_status")
     */
    public function updateStatus(Request $request)
    {
        $id = $request->request->get('id');
        $status = $request->request->get('status');
        $this->inquiryRepository->updateStatus($id, $status);
        return $this->json(['status' => 'success']);
    }

     /**
     * @Route("/%eccube_admin_route%/content/inquiry/delete/{id}", name="admin_content_inquiry_delete", requirements={"id" = "\d+"})
     */
    public function delete(Request $request, $id)
    {
        $inquiry = $this->inquiryRepository->find($id);
        $this->entityManager->remove($inquiry);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_content_inquiry_list');
    }
}