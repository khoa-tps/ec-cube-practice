<?php
namespace Customize\Controller\Admin\Shop;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Customize\Entity\Shop;
use Eccube\Entity\Master\Pref;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use RuntimeException;
use Eccube\Service\FileUploader;

class ShopController extends AbstractController
{
    public function __construct(
    ) {}

    /**
     * @Route("/%eccube_admin_route%/shop", name="admin_shop_list")
     * @Template("@admin/Shop/list.twig")
     */
    public function index()
    {
        $shops = $this->entityManager->getRepository(Shop::class)->findAll();
        return [
            'shops' => $shops,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/shop/create", name="admin_shop_create")
     * @Route("/%eccube_admin_route%/shop/edit/{id}", name="admin_shop_edit")
     * @Template("@admin/Shop/create.twig")
     */
    public function create(Request $request, $id = null)
    {
        $logoSource = null;
        if(is_null($id)){
            $shop = new Shop();
        }else{
            $shop = $this->entityManager->getRepository(Shop::class)->find($id);
            if(!$shop){
                throw $this->createNotFoundException('The shop does not exist');
            }
            $logo = $shop->getLogo();
            if ($logo) {
                $logoSource = 'shops/'.$logo;
            }
        }
        $form = $this->formFactory->createBuilder(FormType::class, $shop)
        ->add('name', TextType::class)
        ->add('kana_name', TextType::class)
        ->add('address01', TextType::class)
        ->add('address02', TextType::class)
        ->add('phone_number', TextType::class)
        ->add('email', TextType::class)
        ->add('logo', FileType::class, [
            'data_class' => null,
            'required' => false,
        ])
        ->add('description', TextareaType::class, [
            'required' => false,
        ])
        ->add('city_id', ChoiceType::class, [
            'choices' => $this->entityManager->getRepository(Pref::class)->findAll(),
            'choice_label' => 'name',
            'choice_value' => 'id',
        ])
        ->add('created_at', DateTimeType::class,
            [
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
            ]
        )
        ->add('updated_at', DateTimeType::class,
            [
                'mapped' => false,
            ]
        )
        ->add('deleted_at', DateTimeType::class,
            [
                'mapped' => false,
            ]
        )
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
            $logoFile = $form->get('logo')->getData();
            if ($logoFile instanceof UploadedFile) {
                try {
                    $shop->setLogo($this->uploadImage($logoFile));
                } catch (RuntimeException $e) {
                    $this->addError('admin.common.save_error', 'admin');
                    return [
                        'form' => $form->createView(),
                    ];
                }
            }
            $shop->setUpdatedAt(new \DateTime());
            $shop->setDeletedAt(null);
            $this->entityManager->persist($shop);
            $this->entityManager->flush();
            return $this->redirectToRoute('admin_shop_list');
        }
        return [
            'form' => $form->createView(),
            'logoSource' => $logoSource,
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
        $targetDir = (string) $this->getParameter('eccube_save_image_dir').'/shops';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $baseFilename = 'shop-'.date('Y-m-d');
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
     * @Route("/%eccube_admin_route%/shop/delete/{id}", name="admin_shop_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $shop = $this->entityManager->getRepository(Shop::class)->find($id);
        if (!$shop) {
            throw $this->createNotFoundException('The shop does not exist');
        }

        $this->entityManager->remove($shop);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_shop_list');
    }
}