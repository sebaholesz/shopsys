<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessorMiddleware;

use Shopsys\FrameworkBundle\Model\Order\Item\OrderItem;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemDataFactory;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface;
use Shopsys\FrameworkBundle\Model\Payment\PaymentPriceCalculation;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;

class AddPaymentMiddleware implements OrderProcessorMiddlewareInterface
{
    public function __construct(
        protected readonly PaymentPriceCalculation $paymentPriceCalculation,
        protected readonly CurrencyFacade $currencyFacade,
        protected readonly OrderItemDataFactory $orderItemDataFactory,
    ) {
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData $orderProcessingData
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface $orderProcessingStack
     * @return \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData
     */
    public function handle(OrderProcessingData $orderProcessingData, OrderProcessingStackInterface $orderProcessingStack): OrderProcessingData
    {
        $payment = $orderProcessingData->cart->getPayment();

        if ($payment === null) {
            return $orderProcessingStack->next()->handle($orderProcessingData, $orderProcessingStack);
        }

        $domainId = $orderProcessingData->domainConfig->getId();

        $currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domainId);

        $paymentPrice = $this->paymentPriceCalculation->calculatePrice(
            $payment,
            $currency,
            $orderProcessingData->orderData->totalPriceByItemType[OrderItem::TYPE_PRODUCT],
            $domainId,
        );

        $orderData = $orderProcessingData->orderData;

        $orderItemData = $this->orderItemDataFactory->create();
        $orderItemData->priceWithoutVat = $paymentPrice->getPriceWithoutVat();
        $orderItemData->priceWithVat = $paymentPrice->getPriceWithVat();
        $orderItemData->totalPriceWithoutVat = $paymentPrice->getPriceWithoutVat();
        $orderItemData->totalPriceWithVat = $paymentPrice->getPriceWithVat();
        /** @todo improve vatpercent? should it be real, or just the set value? */
        $orderItemData->vatPercent = $payment->getPaymentDomain($domainId)->getVat()->getPercent();
        $orderItemData->name = $payment->getName($orderProcessingData->domainConfig->getLocale());
        $orderItemData->quantity = 1;
        $orderItemData->type = OrderItem::TYPE_PAYMENT;

        $orderData->totalPriceByItemType[OrderItem::TYPE_PAYMENT] = $orderProcessingData->orderData->totalPriceByItemType[OrderItem::TYPE_PAYMENT]->add($paymentPrice);
        $orderData->totalPrice = $orderData->totalPrice->add($paymentPrice);

        $orderData->orderPayment = $orderItemData;
        $orderData->payment = $payment;

        $orderData->items[] = $orderItemData;

        $orderData->goPayBankSwift = $orderProcessingData->cart->getPaymentGoPayBankSwift();

        return $orderProcessingStack->next()->handle($orderProcessingData, $orderProcessingStack);
    }
}
