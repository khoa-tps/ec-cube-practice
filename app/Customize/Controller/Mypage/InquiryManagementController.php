<?php

namespace Customize\Controller\Mypage;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eccube\Controller\AbstractController;
use Customize\Repository\InquiryRepository;
use Customize\Entity\Inquiry;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;

class InquiryManagementController extends AbstractController
{
    private $inquiryRepository;

    public function __construct(InquiryRepository $inquiryRepository)
    {
        $this->inquiryRepository = $inquiryRepository;
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
            $Inquiry = $form->getData(); // ← Get data from form
            $Inquiry->setUserId($this->getUser()->getId());
            $Inquiry->setStatus(0);
            $Inquiry->setCreatedAt(new \DateTime());
            $this->entityManager->persist($Inquiry);
            $this->entityManager->flush();
            
            return $this->redirectToRoute('mypage_inquiry');
        }
        return [
            'form' => $form->createView(),
        ];
    }
}
