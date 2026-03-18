<?php

namespace Customize\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Eccube\Repository\LayoutRepository;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Customize\Entity\Mall;

class MallController extends AbstractController
{
  /**
     * @var LayoutRepository
     */
    protected $layoutRepository;

    /**
     * @param LayoutRepository $layoutRepository
     */
    public function __construct(
        LayoutRepository $layoutRepository
    ) {
        $this->layoutRepository = $layoutRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/content/mall", name="admin_content_mall", methods={"GET", "POST"})
     * @Template("@admin/Content/mall.twig")
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $builder = $this->formFactory->createBuilder(FormType::class)
        ->add('publish_date', DateType::class, [
            'required' => false,
            'widget' => 'single_text',
            'html5' => true,
        ])
        ->add('admin_mall_title', TextType::class, [
            'required' => true,
        ])
        ->add('admin_mall_description', TextareaType::class, [
            'required' => false,
        ]);
        
        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mall = new Mall();
            $mall->setAdminMallTitle($form->get('admin_mall_title')->getData());
            $mall->setAdminMallDescription($form->get('admin_mall_description')->getData());
            $mall->setAdminMallPublishDate($form->get('publish_date')->getData());
            $this->entityManager->persist($mall);
            $this->entityManager->flush();


            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_content_mall');
        }else{
            $this->addError('admin.common.save_error', 'admin');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}