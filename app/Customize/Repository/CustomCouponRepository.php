<?php

namespace Customize\Repository;

use Plugin\Coupon42\Repository\CouponRepository;

class CustomCouponRepository extends CouponRepository
{
    /**
     * @param array $searchData
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderBySearchData($searchData)
    {
        $qb = $this->createQueryBuilder('c')->where('c.visible = true');

        if (isset($searchData['id']) && !is_null($searchData['id'])) {
            $qb->andWhere('c.id = :id OR c.coupon_cd LIKE :multi OR c.coupon_name LIKE :multi')
                ->setParameter('id', $searchData['id'])
                ->setParameter('multi', '%' . $searchData['id'] . '%');
        }

        if (isset($searchData['coupon_type']) && !empty($searchData['coupon_type'])) {
            $qb->andWhere('c.coupon_type IN (:coupon_type)')
                ->setParameter('coupon_type', $searchData['coupon_type']);
        }

        if (isset($searchData['enable_flag']) && !empty($searchData['enable_flag'])) {
            $qb->andWhere('c.enable_flag IN (:enable_flag)')
                ->setParameter('enable_flag', $searchData['enable_flag']);
        }

        if (isset($searchData['create_datetime_start']) && !is_null($searchData['create_datetime_start'])) {
            $qb->andWhere('c.create_date >= :create_datetime_start')
                ->setParameter('create_datetime_start', $searchData['create_datetime_start']);
        }

        if (isset($searchData['create_datetime_end']) && !is_null($searchData['create_datetime_end'])) {
            $qb->andWhere('c.create_date <= :create_datetime_end')
                ->setParameter('create_datetime_end', $searchData['create_datetime_end']);
        }

        if (isset($searchData['update_datetime_start']) && !is_null($searchData['update_datetime_start'])) {
            $qb->andWhere('c.update_date >= :update_datetime_start')
                ->setParameter('update_datetime_start', $searchData['update_datetime_start']);
        }

        if (isset($searchData['update_datetime_end']) && !is_null($searchData['update_datetime_end'])) {
            $qb->andWhere('c.update_date <= :update_datetime_end')
                ->setParameter('update_datetime_end', $searchData['update_datetime_end']);
        }

        // Sort
        if (isset($searchData['sortkey']) && !empty($searchData['sortkey'])) {
            $sortKey = $searchData['sortkey'];
            $sortType = isset($searchData['sorttype']) ? $searchData['sorttype'] : 'DESC';
            $qb->orderBy('c.' . $sortKey, $sortType);
        } else {
            $qb->orderBy('c.update_date', 'DESC');
        }

        return $qb;
    }
}
