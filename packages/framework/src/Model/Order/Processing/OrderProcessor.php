<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Order\Processing;

use Shopsys\FrameworkBundle\Component\Domain\Config\DomainConfig;
use Shopsys\FrameworkBundle\Model\Cart\Cart;
use Shopsys\FrameworkBundle\Model\Order\OrderData;

final class OrderProcessor
{
    public function __construct(
        protected readonly OrderProcessingStack $orderProcessingStack,
    ) {

    }

    public function process(OrderData $orderData, Cart $cart, DomainConfig $domainConfig): OrderData
    {
        $orderProcessingData = new OrderProcessingData(
            $cart,
            $orderData,
            $domainConfig,
        );

        $this->orderProcessingStack->rewind();

        try {
            $this->orderProcessingStack->next()->handle($orderProcessingData, $this->orderProcessingStack);
        } catch (NoMoreMiddlewareInStackException) {
            // pass
        }

        return $orderData;
    }

}
