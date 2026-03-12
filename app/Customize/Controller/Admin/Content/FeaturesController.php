<?php

namespace Customize\Controller\Admin\Content;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Customize\Entity\Features;
use Customize\Repository\FeaturesRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Eccube\Repository\CategoryRepository;

class FeaturesController extends AbstractController
{
    private FeaturesRepository $featuresRepository;

    public function __construct(FeaturesRepository $featuresRepository, CategoryRepository $categoryRepository)
    {
        $this->featuresRepository = $featuresRepository;
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * @Route("/%eccube_admin_route%/content/features", name="admin_content_features", methods={"GET", "POST"})
     * @Template("@admin/Content/Features/index.twig")
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $Features = $this->featuresRepository->findAll();

        return [
            'features' => $Features,
        ];
    }

    /**
     * Create a new feature.
     *
     * @Route("/%eccube_admin_route%/content/features/create", name="admin_content_features_create", methods={"GET", "POST"})
     * @Template("@admin/Content/Features/create.twig")
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        $Feature = new Features();
        $Feature->setStatus(1);
        $form = $this->formFactory->createBuilder(FormType::class, $Feature)
            ->add('title', TextType::class, [
                'label' => 'タイトル',
                'required' => true,
            ])
            ->add('catchphrase', TextType::class, [
                'label' => 'キャッチフレーズ',
                'required' => false,
                'mapped' => true,
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
            ->add('related_category_ids', TextType::class, [
                'label' => '関連カテゴリ',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'カテゴリIDをカンマで区切って入力してください',
                ],
            ])
            ->add('keywords', TextType::class, [
                'label' => 'キーワード',
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'キーワードをカンマで区切って入力してください',
                ],
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'サムネイル',
                'required' => false,
                'mapped' => false,
            ])
            ->getForm();  
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $relatedCategoryIds = array_values(array_filter(array_map('trim', explode(',', (string) $form->get('related_category_ids')->getData())), static function ($value) {
                return $value !== '';
            }));
            $keywords = array_values(array_filter(array_map('trim', explode(',', (string) $form->get('keywords')->getData())), static function ($value) {
                return $value !== '';
            }));

            $Feature->setStatus(1);
            $Feature->setRelatedCategoryIds($relatedCategoryIds);
            $Feature->setKeywords($keywords);
            $Feature->setThumbnail('1234567890.jpg');
            $this->entityManager->persist($Feature);
            $this->entityManager->flush();

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_content_features');
        } elseif ($form->isSubmitted()) {
            $this->addError('admin.common.save_error', 'admin');
        }
        $topCategories = $this->categoryRepository->findAll();
        $choicedCategoryIds = [];
        return [
            'form' => $form->createView(),
            'TopCategories' => $topCategories,
            'ChoicedCategoryIds' => $choicedCategoryIds
        ];
    }   

    /**
     * Edit a feature.
     *
     * @Route("/%eccube_admin_route%/content/features/{id}/edit", name="admin_content_features_edit", methods={"GET", "POST"})
     * @Template("@admin/Content/Features/edit.twig")
     * @param Request $request
     * @return array
     */
    public function edit(Request $request)
    {
        $Feature = $this->featuresRepository->find($request->get('id'));
        $form = $this->formFactory->createBuilder(FormType::class, $Feature)
            ->add('title', TextType::class, [
                'label' => 'タイトル',
                'required' => true,
            ])
            ->getForm();
        $form->handleRequest($request);
        $topCategories = $this->categoryRepository->findAll();
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($Feature);
            $this->entityManager->flush();
            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_content_features');
        } elseif ($form->isSubmitted()) {
            $this->addError('admin.common.save_error', 'admin');
        }
        return [
            'form' => $form->createView(),
            'Feature' => $Feature,
            'TopCategories' => $topCategories
        ];
    }
}