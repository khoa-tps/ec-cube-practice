<?php
namespace Customize\Controller\Mypage;

use Eccube\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Plugin\Coupon42\Entity\Coupon;

class CouponManagementController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/mypage/coupon", name="mypage_coupon")
     * @Template("Mypage/coupon_management.twig")
     */
    public function index()
    {
        $Coupons = $this->entityManager->getRepository(Coupon::class)->findAll();
        return [
            'Coupons' => $Coupons,
        ];
    }
}