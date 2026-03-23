<?php
namespace Customize\Controller\Admin\Coupon;

use Plugin\Coupon42\Controller\Admin\CouponController as BaseCouponController;
use Eccube\Common\Constant;
use Plugin\Coupon42\Entity\Coupon;
use Plugin\Coupon42\Form\Type\CouponType;
use Plugin\Coupon42\Repository\CouponDetailRepository;
use Plugin\Coupon42\Repository\CouponRepository;
use Plugin\Coupon42\Service\CouponService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Eccube\Controller\AbstractController;
use Eccube\Repository\CategoryRepository;

class CustomCouponController extends BaseCouponController
{
    /**
     * @var CouponRepository
     */
    private $couponRepository;

    /**
     * @var CouponService
     */
    private $couponService;

    /**
     * @var CouponDetailRepository
     */
    private $couponDetailRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * CouponController constructor.
     *
     * @param CouponRepository $couponRepository
     * @param CouponService $couponService
     * @param CouponDetailRepository $couponDetailRepository
     */
    public function __construct(CouponRepository $couponRepository, CouponService $couponService, CouponDetailRepository $couponDetailRepository,  CategoryRepository $categoryRepository)
    {
        parent::__construct($couponRepository, $couponService, $couponDetailRepository);
        $this->couponRepository = $couponRepository;
        $this->couponService = $couponService;
        $this->couponDetailRepository = $couponDetailRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * クーポンの新規作成/編集確定.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return RedirectResponse|Response
     * @Route("/%eccube_admin_route%/plugin/coupon/new", name="plugin_coupon_new", requirements={"id" = "\d+"})
     * @Route("/%eccube_admin_route%/plugin/coupon/{id}/edit", name="plugin_coupon_edit", requirements={"id" = "\d+"})
     */
    public function edit(Request $request, $id = null)
    {
        $Coupon = null;
        if (!$id) {
            $Coupon = new Coupon();
            $Coupon->setEnableFlag(Constant::ENABLED);
            $Coupon->setVisible(true);
        } else {
            $Coupon = $this->couponRepository->find($id);
            if (!$Coupon) {
                $this->addError('plugin_coupon.admin.notfound', 'admin');

                return $this->redirectToRoute('plugin_coupon_list');
            }
        }

        $form = $this->formFactory->createBuilder(CouponType::class, $Coupon)->getForm();
        if (!$id) {
            $form->get('coupon_cd')->setData($this->couponService->generateCouponCd());
        }
        $details = [];
        $CouponDetails = $Coupon->getCouponDetails();
        foreach ($CouponDetails as $CouponDetail) {
            $details[] = clone $CouponDetail;
            $CouponDetail->getCategoryFullName();
        }
        $TopCategories = $this->categoryRepository->getList(null);
        $ChoicedCategoryIds = [];
        foreach ($CouponDetails as $CouponDetail) {
            $Category = $CouponDetail->getCategory();
            if ($Category !== null) {
                $ChoicedCategoryIds[] = $Category->getId();
            }
        }
        $ChoicedCategoryIds = array_values(array_unique($ChoicedCategoryIds));

        $form->get('CouponDetails')->setData($details);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Coupon = $form->getData();
            $oldReleaseNumber = $request->get('coupon_release_old');
            if (is_null($Coupon->getCouponUseTime())) {
                $Coupon->setCouponUseTime($Coupon->getCouponRelease());
            } else {
                if ($Coupon->getCouponRelease() != $oldReleaseNumber) {
                    $Coupon->setCouponUseTime($Coupon->getCouponRelease());
                }
            }

            $CouponDetails = $this->couponDetailRepository->findBy([
                'Coupon' => $Coupon,
            ]);
            foreach ($CouponDetails as $CouponDetail) {
                $Coupon->removeCouponDetail($CouponDetail);
                $this->entityManager->remove($CouponDetail);
                $this->entityManager->flush($CouponDetail);
            }
            $CouponDetails = $form->get('CouponDetails')->getData();
            foreach ($CouponDetails as $CouponDetail) {
                $CouponDetail->setCoupon($Coupon);
                $CouponDetail->setCouponType($Coupon->getCouponType());
                $CouponDetail->setVisible(false);
                $Coupon->addCouponDetail($CouponDetail);
                $this->entityManager->persist($CouponDetail);
            }
            $this->entityManager->persist($Coupon);
            $this->entityManager->flush($Coupon);
            $this->addSuccess('plugin_coupon.admin.regist.success', 'admin');

            return $this->redirectToRoute('plugin_coupon_list');
        }

        return $this->renderRegistView([
            'form' => $form->createView(),
            'id' => $id,
            'TopCategories' => $TopCategories,
            'ChoicedCategoryIds' => $ChoicedCategoryIds,
        ]);
    }

    /**
     * クーポンコードの新規生成（AJAX用）
     *
     * @Route("/%eccube_admin_route%/plugin/coupon/generate-coupon-cd", name="plugin_coupon_generate_coupon_cd", methods={"GET"})
     */
    public function generateCouponCd(): Response
    {
        $couponCd = $this->couponService->generateCouponCd();

        return $this->json([
            'coupon_cd' => $couponCd,
        ]);
    }
}