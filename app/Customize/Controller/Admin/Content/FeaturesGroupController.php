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
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Customize\Entity\FeaturesGroupLink;
use Symfony\Component\HttpFoundation\Response;

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
        $featureGroups = $this->featuresGroupRepository->getList();
        return [
            'featureGroups' => $featureGroups,
        ];
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
        ])
        ->add('related_category_ids', HiddenType::class, [
            'mapped' => false,
        ]);
        $form->handleRequest($request);
        $Features = $this->featuresRepository->findAll();
        
        if($form->isSubmitted() && $form->isValid()){
            if (is_null($id)) {
                $maxSortNo = $this->entityManager->createQuery('SELECT MAX(f.sort_no) FROM Customize\Entity\FeaturesGroup f')->getSingleScalarResult();
                $featureGroup->setSortNo($maxSortNo ? $maxSortNo + 1 : 1);
            }
            $this->entityManager->persist($featureGroup);
            $this->entityManager->flush();

            // Clear old links for edit case
            $oldLinks = $this->entityManager->getRepository(FeaturesGroupLink::class)->findBy(['features_group' => $featureGroup]);
            foreach ($oldLinks as $link) {
                $this->entityManager->remove($link);
            }
            $this->entityManager->flush();

            // Insert new links
            $relatedCategoryIds = $form->get('related_category_ids')->getData();
            if(!empty($relatedCategoryIds)) {
                $featureIds = explode(',', $relatedCategoryIds);
                foreach ($featureIds as $featureId) {
                    $feature = $this->featuresRepository->find($featureId);
                    if ($feature) {
                        $link = new FeaturesGroupLink();
                        $link->setFeaturesGroup($featureGroup);
                        $link->setFeatures($feature);
                        $this->entityManager->persist($link);
                    }
                }
                $this->entityManager->flush();
            }

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_content_features_group_list');
        }
        // Get existing links to pass to template for pre-checking
        $ChoicedIds = [];
        if (!is_null($featureGroup->getId())) {
            $existingLinks = $featureGroup->getFeaturesGroupLinks();
            foreach ($existingLinks as $link) {
                if ($link->getFeatures()) {
                    $ChoicedIds[] = $link->getFeatures()->getId();
                }
            }
            // Pre-fill the hidden field so it has the right value when the form is submitted without changes
            $form->get('related_category_ids')->setData(implode(',', $ChoicedIds));
        }

        return [
            'form' => $form->createView(),
            'features' => $Features,
            'ChoicedIds' => $ChoicedIds,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/content/features_group_delete/{id}", name="admin_content_features_group_delete", methods={"GET", "POST"})
     * @Template("@admin/Content/FeatureGroup/delete.twig")
     * @param Request $request
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $featureGroup = $this->featuresGroupRepository->find($id);
        if (!$featureGroup) {
            throw $this->createNotFoundException('FeaturesGroup not found');
        }
        $this->entityManager->remove($featureGroup);
        $this->entityManager->flush();
        $this->addSuccess('admin.common.delete_complete', 'admin');
        return $this->redirectToRoute('admin_content_features_group_list');
    }

    /**
     * @Route("/%eccube_admin_route%/content/features_group/sort_no/move", name="admin_content_features_group_sort_no_move", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function sortNoMove(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            foreach ($request->request->all() as $id => $sortNo) {
                $featureGroup = $this->featuresGroupRepository->find($id);
                if ($featureGroup) {
                    $featureGroup->setSortNo($sortNo);
                    $this->entityManager->persist($featureGroup);
                }
            }
            $this->entityManager->flush();
            return new Response();
        }

        return new Response('Bad Request', 400);
    }
}