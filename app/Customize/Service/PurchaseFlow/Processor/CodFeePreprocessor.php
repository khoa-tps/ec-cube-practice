<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Service\PurchaseFlow\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Payment;
use Eccube\Repository\Master\OrderItemTypeRepository;
use Eccube\Repository\Master\TaxDisplayTypeRepository;
use Eccube\Repository\Master\TaxTypeRepository;
use Eccube\Service\PurchaseFlow\ItemHolderPreprocessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;

class CodFeePreprocessor implements ItemHolderPreprocessor
{
    public const COD_FEE = 10;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OrderItemTypeRepository
     */
    private $orderItemTypeRepository;

    /**
     * @var TaxDisplayTypeRepository
     */
    private $taxDisplayTypeRepository;

    /**
     * @var TaxTypeRepository
     */
    private $taxTypeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrderItemTypeRepository $orderItemTypeRepository,
        TaxDisplayTypeRepository $taxDisplayTypeRepository,
        TaxTypeRepository $taxTypeRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->orderItemTypeRepository = $orderItemTypeRepository;
        $this->taxDisplayTypeRepository = $taxDisplayTypeRepository;
        $this->taxTypeRepository = $taxTypeRepository;
    }

    public function process(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        if (!$itemHolder instanceof Order) {
            return;
        }

        $Payment = $itemHolder->getPayment();
        if (!$Payment instanceof Payment || !$Payment->getId()) {
            $this->removeCodFeeItems($itemHolder);

            return;
        }

        if (!$this->isCodPayment($Payment)) {
            $this->removeCodFeeItems($itemHolder);

            return;
        }

        foreach ($itemHolder->getItems() as $item) {
            if ($item->getProcessorName() === self::class) {
                $item->setPrice(self::COD_FEE);

                return;
            }
        }

        $this->addCodFeeItem($itemHolder);
    }

    private function isCodPayment(Payment $Payment): bool
    {
        $method = (string) $Payment->getMethod();

        return $method === 'COD'
            || $method === '代金引換'
            || str_contains($method, 'COD')
            || str_contains($method, '代金引換');
    }

    private function addCodFeeItem(Order $Order): void
    {
        $OrderItemType = $this->orderItemTypeRepository->find(OrderItemType::CHARGE);
        $TaxDisplayType = $this->taxDisplayTypeRepository->find(TaxDisplayType::INCLUDED);
        $Taxation = $this->taxTypeRepository->find(TaxType::TAXATION);

        $item = new OrderItem();
        $item->setProductName('COD手数料')
            ->setQuantity(1)
            ->setPrice(self::COD_FEE)
            ->setOrderItemType($OrderItemType)
            ->setOrder($Order)
            ->setTaxDisplayType($TaxDisplayType)
            ->setTaxType($Taxation)
            ->setProcessorName(self::class);

        $Order->addItem($item);
    }

    private function removeCodFeeItems(Order $Order): void
    {
        foreach ($Order->getItems() as $item) {
            if ($item->getProcessorName() === self::class) {
                $Order->removeOrderItem($item);
                $this->entityManager->remove($item);
            }
        }
    }
}

