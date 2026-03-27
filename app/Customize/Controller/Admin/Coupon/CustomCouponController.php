<?php
namespace Customize\Controller\Admin\Coupon;

use Plugin\Coupon42\Controller\Admin\CouponController as BaseCouponController;
use Eccube\Common\Constant;
use Plugin\Coupon42\Entity\Coupon;
use Plugin\Coupon42\Entity\CouponDetail;
use Plugin\Coupon42\Form\Type\CouponType;
use Plugin\Coupon42\Repository\CouponDetailRepository;
use Plugin\Coupon42\Repository\CouponRepository;
use Customize\Repository\CustomCouponRepository;
use Plugin\Coupon42\Service\CouponService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Attribute\Route;
use Eccube\Controller\AbstractController;
use Eccube\Repository\CategoryRepository;
use Plugin\Coupon42\Form\Type\Admin\CouponSearchType;
use Knp\Component\Pager\PaginatorInterface;
use Eccube\Repository\Master\PageMaxRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CustomCouponController extends BaseCouponController
{
    public function __construct(
        private CustomCouponRepository $couponRepository,
        private CouponService $couponService,
        private CouponDetailRepository $couponDetailRepository,
        private CategoryRepository $categoryRepository,
        private PaginatorInterface $paginator,
        private PageMaxRepository $pageMaxRepository
    ) {
        parent::__construct(
            $couponRepository,
            $couponService,
            $couponDetailRepository
        );
    }

    #[Route('/%eccube_admin_route%/plugin/coupon', name: 'plugin_coupon_list')]
    #[Route('/%eccube_admin_route%/plugin/coupon/page/{page_no}', name: 'plugin_coupon_list_page', requirements: ['page_no' => '\d+'])]
    #[Template('@Coupon42/admin/index.twig')]
    public function index(Request $request, ?int $page_no = null): array
    {
        $searchForm = $this->formFactory
            ->createBuilder(CouponSearchType::class)
            ->getForm();

        $searchData = [
            'id' => null,
            'coupon_type' => [],
            'enable_flag' => [],
            'create_datetime_start' => null,
            'create_datetime_end' => null,
            'update_datetime_start' => null,
            'update_datetime_end' => null,
        ];

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
            if ($searchForm->isSubmitted() && $searchForm->isValid()) {
                $searchData = $searchForm->getData();
            }
        } else {
            $searchData = $request->query->all();
            $searchForm->submit($searchData);
            $searchData = $searchForm->getData();
        }

        $qb = $this->couponRepository->getQueryBuilderBySearchData($searchData);

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->get('page_no', 1),
            $request->query->get('page_count', 20)
        );

        $pageMaxis = $this->pageMaxRepository->findBy([], ['id' => 'ASC', 'sort_no' => 'ASC']);
        $pageCount = $this->session->get('eccube.admin.coupon.search.page_count', $this->eccubeConfig['eccube_default_page_count']);
        $pageCountParam = $request->get('page_count');
        if ($pageCountParam && is_numeric($pageCountParam)) {
            foreach ($pageMaxis as $pageMax) {
                if ($pageCountParam == $pageMax->getName()) {
                    $pageCount = $pageMax->getName();
                    $this->session->set('eccube.admin.coupon.search.page_count', $pageCount);
                    break;
                }
            }
        }

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'Coupons' => $pagination,
            'has_errors' => $searchForm->isSubmitted() && !$searchForm->isValid(),
            'pageMaxis' => $pageMaxis,
            'page_count' => $pageCount,
            'page_no' => $page_no,
        ];
    }

    /**
     * クーポンの新規作成/編集確定.
     */
    #[Route('/%eccube_admin_route%/plugin/coupon/new', name: 'plugin_coupon_new')]
    #[Route('/%eccube_admin_route%/plugin/coupon/{id}/edit', name: 'plugin_coupon_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, $id = null): Response

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
        $adminProduct = $request->get('admin_product');
        if ($request->isMethod('POST') && is_array($adminProduct) && isset($adminProduct['Category'])) {
            $ChoicedCategoryIds = (array) $adminProduct['Category'];
        } else {
            $ChoicedCategoryIds = [];
            foreach ($CouponDetails as $CouponDetail) {
                if ($categoryId = $CouponDetail->getCategory()?->getId()) {
                    $ChoicedCategoryIds[] = $categoryId;
                }
            }
        }
        $ChoicedCategoryIds = array_values(array_unique($ChoicedCategoryIds));

        $form->get('CouponDetails')->setData($details);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $Coupon = $form->getData();

            // 既存の明細を一旦削除
            $oldDetails = $this->couponDetailRepository->findBy(['Coupon' => $Coupon]);
            foreach ($oldDetails as $detail) {
                $this->entityManager->remove($detail);
            }
            $this->entityManager->flush();

            // 1. フォーム経由の明細（商品等）を保存
            $CouponDetailsFromForm = $form->get('CouponDetails')->getData();
            foreach ($CouponDetailsFromForm as $CouponDetail) {
                $CouponDetail->setCoupon($Coupon);
                $CouponDetail->setCouponType($Coupon->getCouponType());
                $CouponDetail->setVisible(false);
                $this->entityManager->persist($CouponDetail);
            }

            // 2. マクロツリー（カテゴリー）を保存
            $adminProduct = $request->get('admin_product');
            $categoryIds = (is_array($adminProduct) && isset($adminProduct['Category'])) ? (array)$adminProduct['Category'] : [];
            if (!empty($categoryIds)) {
                // カテゴリーが選바れている場合は、適切なタイプ（CATEGORY=2）を設定
                $Coupon->setCouponType(Coupon::CATEGORY);
                foreach ($categoryIds as $categoryId) {
                    $Category = $this->categoryRepository->find($categoryId);
                    if ($Category) {
                        $CouponDetail = new CouponDetail();
                        $CouponDetail->setCoupon($Coupon);
                        $CouponDetail->setCategory($Category);
                        $CouponDetail->setCouponType(Coupon::CATEGORY);
                        $CouponDetail->setVisible(false);
                        $this->entityManager->persist($CouponDetail);
                    }
                }
            }

            $oldReleaseNumber = $request->get('coupon_release_old');
            if (is_null($Coupon->getCouponUseTime())) {
                $Coupon->setCouponUseTime($Coupon->getCouponRelease());
            } else {
                if ($Coupon->getCouponRelease() != $oldReleaseNumber) {
                    $Coupon->setCouponUseTime($Coupon->getCouponRelease());
                }
            }

            $this->entityManager->persist($Coupon);
            $this->entityManager->flush();
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
     */
    #[Route('/%eccube_admin_route%/plugin/coupon/generate-coupon-cd', name: 'plugin_coupon_generate_coupon_cd', methods: ['GET'])]
    public function generateCouponCd(): Response
    {
        $couponCd = $this->couponService->generateCouponCd();

        return $this->json([
            'coupon_cd' => $couponCd,
        ]);
    }

    /**
     * クーポンCSVの出力.
     */
    #[Route('/%eccube_admin_route%/plugin/coupon/export', name: 'plugin_coupon_export', methods: ['GET'])]
    public function export(Request $request): StreamedResponse
    {
        // タイムアウトを無効にする.
        set_time_limit(0);

        // クーポンデータを取得
        $Coupons = $this->couponRepository->findBy(
            ['visible' => true],
            ['id' => 'DESC']
        );

        $response = new StreamedResponse();
        $response->setCallback(function () use ($Coupons) {
            $handle = fopen('php://output', 'w');
            
            // BOM (Excel等での文字化け防止)
            fwrite($handle, "\xEF\xBB\xBF");

            // ヘッダ行の出力
            $headers = [
                $this->translator->trans('plugin_coupon.admin.index.col01'),
                $this->translator->trans('plugin_coupon.admin.index.col02'),
                $this->translator->trans('plugin_coupon.admin.index.col03'),
                $this->translator->trans('plugin_coupon.admin.index.col04'),
                $this->translator->trans('plugin_coupon.admin.index.col05'),
                $this->translator->trans('plugin_coupon.admin.index.col06'),
                $this->translator->trans('plugin_coupon.admin.index.col07'),
                $this->translator->trans('plugin_coupon.admin.index.col08'),
                $this->translator->trans('plugin_coupon.admin.index.col09'),
                $this->translator->trans('plugin_coupon.admin.index.col11'), // Status
            ];
            fputcsv($handle, $headers);

            foreach ($Coupons as $Coupon) {
                // クーポン種別
                $type = '';
                if ($Coupon->getCouponType() == 1) {
                    $type = $this->translator->trans('plugin_coupon.admin.coupon_type.product');
                } elseif ($Coupon->getCouponType() == 2) {
                    $type = $this->translator->trans('plugin_coupon.admin.coupon_type.category');
                } elseif ($Coupon->getCouponType() == 3) {
                    $type = $this->translator->trans('plugin_coupon.admin.coupon_type.all');
                }

                // 会員限定
                $member = ($Coupon->getCouponMember() == 1) 
                    ? $this->translator->trans('plugin_coupon.admin.coupon_member.yes') 
                    : $this->translator->trans('plugin_coupon.admin.coupon_member.no');

                // 値引き情報
                $discount = '';
                if ($Coupon->getDiscountType() == 1) {
                    $discount = $Coupon->getDiscountPrice();
                } elseif ($Coupon->getDiscountType() == 2) {
                    $discount = $Coupon->getDiscountRate() . '%';
                }

                // ステータス
                $status = ($Coupon->getEnableFlag() == 1) 
                    ? $this->translator->trans('common.enabled') 
                    : $this->translator->trans('common.disabled');

                $row = [
                    $Coupon->getId(),
                    $Coupon->getCouponCd(),
                    $Coupon->getCouponName(),
                    $type,
                    $member,
                    $discount,
                    $Coupon->getCouponUseTime() . ' / ' . $Coupon->getCouponRelease(),
                    $Coupon->getCouponLowerLimit(),
                    $Coupon->getAvailableFromDate()->format('Y-m-d') . ' ～ ' . $Coupon->getAvailableToDate()->format('Y-m-d'),
                    $status
                ];
                fputcsv($handle, $row);
            }
            fclose($handle);
        });

        $now = new \DateTime();
        $filename = 'coupon_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);

        return $response;
    }
}