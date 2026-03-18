<?php
namespace Customize\Entity;

use Eccube\Entity\Order as BaseOrder;

class Order extends BaseOrder
{
    use OrderTrait;
}