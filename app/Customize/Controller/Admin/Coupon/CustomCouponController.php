<?php
namespace Customize\Controller\Admin\Coupon;

use Plugin\Coupon42\Controller\Admin\CouponController as BaseCouponController;
use Eccube\Common\Constant;
use Eccube\Form\Type\Admin\SearchProductType;
use Plugin\Coupon42\Entity\Coupon;
use Plugin\Coupon42\Entity\CouponDetail;
use Plugin\Coupon42\Form\Type\CouponSearchCategoryType;
use Plugin\Coupon42\Form\Type\CouponType;
use Plugin\Coupon42\Repository\CouponDetailRepository;
use Plugin\Coupon42\Repository\CouponRepository;
use Plugin\Coupon42\Service\CouponService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            // 新規登録
            $Coupon = new Coupon();
            $Coupon->setEnableFlag(Constant::ENABLED);
            $Coupon->setVisible(true);
        } else {
            // 更新
            $Coupon = $this->couponRepository->find($id);
            if (!$Coupon) {
                $this->addError('plugin_coupon.admin.notfound', 'admin');

                return $this->redirectToRoute('plugin_coupon_list');
            }
        }

        $form = $this->formFactory->createBuilder(CouponType::class, $Coupon)->getForm();
        // クーポンコードの発行
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
        // CouponType に Category フィールドは無い。カテゴリ対象は CouponDetails 内の Category 参照（Product 画面の form.Category 相当）
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
            /** @var \Plugin\Coupon42\Entity\Coupon $Coupon */
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
            /** @var CouponDetail $CouponDetail */
            foreach ($CouponDetails as $CouponDetail) {
                $CouponDetail->setCoupon($Coupon);
                $CouponDetail->setCouponType($Coupon->getCouponType());
                $CouponDetail->setVisible(false);
                $Coupon->addCouponDetail($CouponDetail);
                $this->entityManager->persist($CouponDetail);
            }
            $this->entityManager->persist($Coupon);
            $this->entityManager->flush($Coupon);
            // 成功時のメッセージを登録する
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
}