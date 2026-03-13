<?php
namespace Customize\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Customize\Repository\FeaturesRepository;
use Customize\Repository\FeaturesGroupRepository;
use Customize\Entity\FeaturesGroup;

class FeaturesGroupController extends AbstractController
{

    public function __construct(FeaturesRepository $featuresRepository, FeaturesGroupRepository $featuresGroupRepository)
    {
        $this->featuresRepository = $featuresRepository;
        $this->featuresGroupRepository = $featuresGroupRepository;;  
    }
    /**
     * @Route("/%eccube_admin_route%/content/features_group_list", name="admin_content_features_group_list", methods={"GET", "POST"})
     * @Template("@admin/Content/FeatureGroup/index.twig")
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        return [];
    }

    /**
     * @Route("/%eccube_admin_route%/content/features_group_create", name="admin_content_features_group_create", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/content/features_group_edit/{id}", name="admin_content_features_group_edit", methods={"GET", "POST"})
     * @Template("@admin/Content/FeatureGroup/create.twig")
     * @param Request $request
     * @return array
     */
    public function create(Request $request, $id = null)
    {
        if(is_null($id)){
            $featureGroup = new FeaturesGroup();
        } else{
            $featureGroup = $this->featuresGroupRepository->find($id);
        }

        $form = $this->createForm(FormType::class, $featureGroup)
        ->add('name', TextType::class, [
            'label' => 'タイトル',
            'required' => true,
        ])
        ->add('description', TextareaType::class, [
            'label' => '説明',
            'required' => false,
            'mapped' => true,
        ])
        ->add('publish_date_from', DateTimeType::class, [
            'label' => '公開日時',
            'required' => true,
            'widget' => 'single_text',
            'html5' => true,
        ])->add('publish_date_to', DateTimeType::class, [
            'label' => '公開日時',
            'required' => true,
            'widget' => 'single_text',
            'html5' => true,
        ])  ;
        $form->handleRequest($request);
        $Features = $this->featuresRepository->findAll();
        
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($featureGroup);
            $this->entityManager->flush();

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_content_features_group_list');
        }
        
        return [
            'form' => $form->createView(),
            'features' => $Features
        ];
    }
}