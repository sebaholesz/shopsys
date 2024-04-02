<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessorMiddleware;

use App\Model\Order\Item\OrderItemDataFactory;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItem;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\FrameworkBundle\Model\Transport\TransportPriceCalculation;

class AddTransportMiddleware implements OrderProcessorMiddlewareInterface
{
    public function __construct(
        protected readonly TransportPriceCalculation $transportPriceCalculation,
        protected readonly CurrencyFacade $currencyFacade,
        protected readonly OrderItemDataFactory $orderItemDataFactory,
    ) {
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData $orderProcessingData
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface $orderProcessingStack
     * @return \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData
     */
    public function handle(OrderProcessingData $orderProcessingData, OrderProcessingStackInterface $orderProcessingStack,): OrderProcessingData
    {
        $transport = $orderProcessingData->cart->getTransport();

        if ($transport !== null) {
            $domainId = $orderProcessingData->domainConfig->getId();

            $currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domainId);

            $transportPrice = $this->transportPriceCalculation->calculatePrice(
                $transport,
                $currency,
                $orderProcessingData->orderData->totalPriceByItemType[OrderItem::TYPE_PRODUCT],
                $domainId,
            );

            $orderData = $orderProcessingData->orderData;

            $orderItemData = $this->orderItemDataFactory->create();
            $orderItemData->priceWithoutVat = $transportPrice->getPriceWithoutVat();
            $orderItemData->priceWithVat = $transportPrice->getPriceWithVat();
            $orderItemData->totalPriceWithoutVat = $transportPrice->getPriceWithoutVat();
            $orderItemData->totalPriceWithVat = $transportPrice->getPriceWithVat();
            $orderItemData->vatPercent = $transport->getTransportDomain($domainId)->getVat()->getPercent();
            $orderItemData->name = $transport->getName($orderProcessingData->domainConfig->getLocale());
            $orderItemData->quantity = 1;
            $orderItemData->type = OrderItem::TYPE_TRANSPORT;

            $orderData->orderTransport = $orderItemData;
            $orderData->transport = $transport;

            $orderData->totalPriceByItemType[OrderItem::TYPE_TRANSPORT] = $orderData->totalPriceByItemType[OrderItem::TYPE_TRANSPORT]->add($transportPrice);
            $orderData->totalPrice = $orderProcessingData->orderData->totalPrice->add($transportPrice);

            $orderData->items[] = $orderItemData;
        }

        return $orderProcessingStack->next()->handle($orderProcessingData, $orderProcessingStack);
    }
}
