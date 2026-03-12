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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FeaturesController extends AbstractController
{
    private FeaturesRepository $featuresRepository;
    private CategoryRepository $categoryRepository;

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
     * @Route("/%eccube_admin_route%/content/features/{id}/edit", requirements={"id" = "\d+"}, name="admin_content_features_edit", methods={"GET", "POST"})
     * @Template("@admin/Content/Features/create.twig")
     * @param Request $request
     * @return array
     */
    public function create(Request $request, $id = null)
    {
        if (is_null($id)) {
            $Feature = new Features();
        } else {
            $Feature = $this->featuresRepository->find($id);
            if (!$Feature) {
                throw new NotFoundHttpException();
            }
        }
        $topCategories = $this->categoryRepository->findAll();
        $choicedCategoryIds = is_array($Feature->getRelatedCategoryIds()) ? $Feature->getRelatedCategoryIds() : [];
        $keywords = is_array($Feature->getKeywords()) ? $Feature->getKeywords() : [];

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
                'data' => implode(',', $choicedCategoryIds),
                'attr' => [
                    'placeholder' => 'カテゴリIDをカンマで区切って入力してください',
                ],
            ])
            ->add('keywords', TextType::class, [
                'label' => 'キーワード',
                'required' => false,
                'mapped' => false,
                'data' => implode(',', $keywords),
                'attr' => [
                    'placeholder' => 'キーワードをカンマで区切って入力してください',
                ],
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'サムネイル',
                'required' => false,
                'mapped' => false,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'ステータス',
                'required' => true,
                'choices' => [
                    '公開' => 1,
                    '非公開' => 0,
                ],
            ])
            ->getForm();  
            
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $relatedCategoryIds = array_values(array_filter(array_map('trim', explode(',', (string) $form->get('related_category_ids')->getData())), static function ($value) {
                return $value !== '';
            }));
            $choicedCategoryIds = $relatedCategoryIds;
            $keywords = array_values(array_filter(array_map('trim', explode(',', (string) $form->get('keywords')->getData())), static function ($value) {
                return $value !== '';
            }));

            $imageFile = $form->get('thumbnail')->getData();
            if ($imageFile instanceof UploadedFile) {
                try {
                    $Feature->setThumbnail($this->uploadImage($imageFile));
                } catch (RuntimeException $e) {
                    $this->addError('admin.common.save_error', 'admin');
                    return [
                        'form' => $form->createView(),
                        'TopCategories' => $topCategories,
                        'ChoicedCategoryIds' => $choicedCategoryIds,
                    ];
                }
            }

            $Feature->setRelatedCategoryIds($relatedCategoryIds);
            $Feature->setKeywords($keywords);
            $this->entityManager->persist($Feature);
            $this->entityManager->flush();

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_content_features');
        } elseif ($form->isSubmitted()) {
            $choicedCategoryIds = array_values(array_filter(array_map('trim', explode(',', (string) $form->get('related_category_ids')->getData())), static function ($value) {
                return $value !== '';
            }));
            $this->addError('admin.common.save_error', 'admin');
        }

        return [
            'form' => $form->createView(),
            'TopCategories' => $topCategories,
            'ChoicedCategoryIds' => $choicedCategoryIds,
        ];
    }   

    /**
     * Upload an image.
     *
     * @param UploadedFile $imageFile
     * @return string
     */
    private function uploadImage(UploadedFile $imageFile): string
    {
        $extension = $imageFile->guessExtension();
        if (!$extension) {
            $extension = strtolower((string) $imageFile->getClientOriginalExtension());
        }
        if ($extension === '') {
            $extension = 'bin';
        }
        $targetDir = (string) $this->getParameter('eccube_save_image_dir').'/features';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $baseFilename = 'feature-'.date('Y-m-d');
        $newFilename = sprintf('%s.%s', $baseFilename, $extension);
        $sequence = 1;
        while (file_exists($targetDir.'/'.$newFilename)) {
            $newFilename = sprintf('%s-%d.%s', $baseFilename, $sequence, $extension);
            ++$sequence;
        }

        try {
            $imageFile->move($targetDir, $newFilename);
        } catch (\Throwable $e) {
            throw new RuntimeException('Failed to upload thumbnail image.', 0, $e);
        }

        return $newFilename;
    }

    /**
     * Delete a feature.
     * @Route("/%eccube_admin_route%/content/features/{id}/delete", name="admin_content_features_delete", methods={"DELETE"})
     * @param Request $request
     * @return array
     */
    public function delete(Request $request, $id)
    {
        $this->isTokenValid();
        $Feature = $this->featuresRepository->find($id);
        $success = $Feature ? true : false;
        if ($request->isXmlHttpRequest()) {
            return $this->json([
                'success' => $success,
                'message' => $success ? 'admin.common.delete_complete' : 'admin.common.delete_error',
            ]);
        }
        if (!$Feature) {
            $this->deleteMessage();
            return [
                'success' => false,
                'message' => 'admin.common.delete_error',
            ];
        }
        $this->entityManager->remove($Feature);
        $this->entityManager->flush();
        //Delete image
        $this->deleteImage($Feature->getThumbnail());
        $this->addSuccess('admin.common.delete_complete', 'admin');
        return $this->redirectToRoute('admin_content_features');
    }

    /**
     * Delete an image.
     *
     * @param string $imageName
     * @return void
     */
    private function deleteImage(string $imageName): void
    {
        if (file_exists($this->getParameter('eccube_save_image_dir').'/features/'.$imageName)) {
            unlink($this->getParameter('eccube_save_image_dir').'/features/'.$imageName);
        }
    }
}