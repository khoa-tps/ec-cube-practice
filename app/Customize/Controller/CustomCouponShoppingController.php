<?php

namespace Customize\Controller;

use Customize\Config\CouponConfig;
use Plugin\Coupon42\Form\Type\CouponUseType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Plugin\Coupon42\Controller\CouponShoppingController;
use Plugin\Coupon42\Entity\Coupon;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Eccube\Entity\Order;
use Eccube\Repository\DeliveryTimeRepository;
use Eccube\Service\CartService;
use Eccube\Service\OrderHelper;
use Plugin\Coupon42\Service\CouponService;
use Plugin\Coupon42\Repository\CouponRepository;
use Plugin\Coupon42\Repository\CouponOrderRepository;

class CustomCouponShoppingController extends CouponShoppingController
{
    protected $deliveryTimeRepository;
    protected $cartService;
    protected $orderHelper;
    protected $couponService;
    protected $couponRepository;
    protected $couponOrderRepository;

    public function __construct(
        DeliveryTimeRepository $deliveryTimeRepository,
        CartService $cartService,
        OrderHelper $orderHelper,
        CouponService $couponService,
        CouponRepository $couponRepository,
        CouponOrderRepository $couponOrderRepository
    ) {
        parent::__construct($deliveryTimeRepository, $cartService, $orderHelper, $couponService, $couponRepository, $couponOrderRepository);
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->cartService = $cartService;
        $this->orderHelper = $orderHelper;
        $this->couponService = $couponService;
        $this->couponRepository = $couponRepository;
        $this->couponOrderRepository = $couponOrderRepository;
    }
    /**
     * クーポン入力、登録画面.
     *
     * @param Request $request
     *
     * @return array|RedirectResponse
     * @Route("/plugin/coupon/shopping/shopping_coupon", name="plugin_coupon_shopping")
     * @Template("Coupon42/Resource/template/default/shopping_coupon.twig")
     *
     * @see https://github.com/EC-CUBE/coupon-plugin/issues/128
     */
    public function shoppingCoupon(Request $request)
    {
        $preOrderId = $this->cartService->getPreOrderId();
        /** @var Order $Order */
        $Order = $this->orderHelper->getPurchaseProcessingOrder($preOrderId);

        if (!$Order) {
            $this->addError('front.shopping.order_error');

            return $this->redirectToRoute('shopping_error');
        }
        $form = $this->formFactory->createBuilder(CouponUseType::class)->getForm();
        // クーポンコードを取得する
        $CouponOrder = $this->couponOrderRepository->getCouponOrder($Order->getPreOrderId());
        $couponCd = null;
        if ($CouponOrder) {
            $couponCd = $CouponOrder->getCouponCd();
        }

        $form->get('coupon_cd')->setData($couponCd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // サービスの取得
            /** @var CouponService $service */
            $service = $this->couponService;
            $formCouponCd = $form->get('coupon_cd')->getData();
            $formCouponCancel = $form->get('coupon_use')->getData();
            // ---------------------------------
            // クーポンコード入力項目追加
            // ----------------------------------
            if ($formCouponCancel == 0) {
                // クーポンを利用しない
                $this->couponService->removeCouponOrder($Order);
                return $this->redirectToRoute('shopping');
            } else {
                // クーポンを利用する
                $discount = 0;
                $error = false;
                // クーポン情報を取得
                /* @var $Coupon Coupon */
                $Coupon = $this->couponRepository->findActiveCoupon($formCouponCd);
                
                if (!$Coupon) {
                    $form->get('coupon_cd')->addError(new FormError(trans('plugin_coupon.front.shopping.notexists')));
                    $error = true;
                }
                // Validate target user
                $targetUsers = $Coupon->getTargetUsers() ?: [];
                $currentMonth = date('m'); // Lấy tháng hiện tại (01 đến 12)

                if (!empty($targetUsers)) {
                    // Nếu khách chưa đăng nhập (GUEST)
                    if (!$this->isGranted('ROLE_USER')) {
                        // Bắt buộc đăng nhập nếu mã coupon yêu cầu đối tượng User
                        if (in_array(CouponConfig::TARGET_USERS_NEW, $targetUsers) || in_array(CouponConfig::TARGET_USERS_BIRTHDAY, $targetUsers)) {
                            $form->get('coupon_cd')->addError(new FormError(trans('plugin_coupon.front.shopping.member')));
                            $error = true;
                        }
                    } else {
                        // Khách ĐÃ đăng nhập
                        $Customer = $this->getUser();

                        // 1. Kiểm tra ưu đãi cho Khách Hàng Mới trong tháng
                        if (in_array(CouponConfig::TARGET_USERS_NEW, $targetUsers)) {
                            if ($Customer->getCreateDate()->format('m') !== $currentMonth) {
                                $form->get('coupon_cd')->addError(new FormError('This promotional code is only for new customers who register this month.'));
                                $error = true;
                            }
                        }

                        // 2. Kiểm tra ưu đãi cho Khách Hàng Sinh Nhật trong tháng
                        if (in_array(CouponConfig::TARGET_USERS_BIRTHDAY, $targetUsers)) {
                            $userBirth = $Customer->getBirth(); // Ngày sinh có thể bị Null nếu khách không nhập
                            if (!$userBirth || $userBirth->format('m') !== $currentMonth) {
                                $form->get('coupon_cd')->addError(new FormError('This promotional code is only for customers who have a birthday this month.'));
                                $error = true;
                            }
                        }
                    }
                }
                if ($this->isGranted('ROLE_USER')) {
                    $Customer = $this->getUser();
                } else {
                    $Customer = $this->orderHelper->getNonMember();
                    if ($Coupon) {
                        if ($Coupon->getCouponMember()) {
                            $form->get('coupon_cd')->addError(new FormError(trans('plugin_coupon.front.shopping.member')));
                            $error = true;
                        }
                    }
                }

                $couponUsedOrNot = $this->couponService->checkCouponUsedOrNot($formCouponCd, $Customer);
                if ($Coupon && $couponUsedOrNot) {
                    // 既に存在している
                    $form->get('coupon_cd')->addError(new FormError(trans('plugin_coupon.front.shopping.sameuser')));
                    $error = true;
                }

                // ----------------------------------
                // 値引き項目追加 / 合計金額上書き
                // ----------------------------------
                if (!$error && $Coupon) {
                    $couponProducts = $service->existsCouponProduct($Coupon, $Order);
                    $discount = $service->recalcOrder($Coupon, $couponProducts);

                    // クーポン情報を登録
                    $service->saveCouponOrder($Order, $Coupon, $formCouponCd, $Customer, $discount);

                    return $this->redirectToRoute('shopping');
                } else {
                    // エラーが発生した場合、前回設定されているクーポンがあればその金額を再設定する
                    if ($couponCd && $Coupon) {
                        // クーポン情報を取得
                        $Coupon = $this->couponRepository->findActiveCoupon($couponCd);
                        if ($Coupon) {
                            $couponProducts = $service->existsCouponProduct($Coupon, $Order);
                            // 値引き額を取得
                            $discount = $service->recalcOrder($Coupon, $couponProducts);
                            // クーポン情報を登録
                            $service->saveCouponOrder($Order, $Coupon, $couponCd, $Customer, $discount);
                        }
                    }
                }
            }
        }

        return [
            'form' => $form->createView(),
            'Order' => $Order,
        ];
    }
}
