<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessorMiddleware;

use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;
use Shopsys\FrameworkBundle\Model\Pricing\Price;

class AddRoundingMiddleware implements OrderProcessorMiddlewareInterface
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData $orderProcessingData
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface $orderProcessingStack
     * @return \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData
     */
    public function handle(OrderProcessingData $orderProcessingData, OrderProcessingStackInterface $orderProcessingStack,): OrderProcessingData
    {
        //d(__CLASS__);

/*
                if (!$payment->isCzkRounding() || $currency->getCode() !== Currency::CODE_CZK) {
            return null;
        }

        $priceWithVat = $orderTotalPrice->getPriceWithVat();
        $roundedPriceWithVat = $priceWithVat->round(0);

        $roundingPrice = $this->rounding->roundPriceWithVatByCurrency(
            $roundedPriceWithVat->subtract($priceWithVat),
            $currency,
        );

        if ($roundingPrice->isZero()) {
            return null;
        }

        return new Price($roundingPrice, $roundingPrice);*/

        return $orderProcessingStack->next()->handle($orderProcessingData, $orderProcessingStack);
    }
}
