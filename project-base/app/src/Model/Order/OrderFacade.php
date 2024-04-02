<?php

declare(strict_types=1);

namespace App\Model\Order;

use App\Model\Transport\Type\TransportType;
use Override;
use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderFacade as BaseOrderFacade;

/**
 * @property \App\Model\Order\OrderRepository $orderRepository
 * @property \App\Model\Customer\User\CustomerUserFacade $customerUserFacade
 * @property \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusRepository $orderStatusRepository
 * @property \App\Model\Order\Mail\OrderMailFacade $orderMailFacade
 * @property \App\Component\Setting\Setting $setting
 * @property \App\Model\Order\PromoCode\CurrentPromoCodeFacade $currentPromoCodeFacade
 * @property \App\Model\Cart\CartFacade $cartFacade
 * @property \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
 * @property \Shopsys\FrameworkBundle\Model\Transport\TransportPriceCalculation $transportPriceCalculation
 * @property \App\Model\Order\Item\OrderItemFactory $orderItemFactory
 * @method \App\Model\Order\Order[] getCustomerUserOrderList(\App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order[] getCustomerUserOrderLimitedList(\App\Model\Customer\User\CustomerUser $customerUser, int $limit, int $offset)
 * @method int getCustomerUserOrderCount(\App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order[] getOrderListForEmailByDomainId(string $email, int $domainId)
 * @method \App\Model\Order\Order getById(int $orderId)
 * @method \App\Model\Order\Order getByUuid(string $uuid)
 * @method \App\Model\Order\Order getByUuidAndCustomerUser(string $uuid, \App\Model\Customer\User\CustomerUser $customerUser)
 * @method \App\Model\Order\Order getByUuidAndUrlHash(string $uuid, string $urlHash)
 * @method \App\Model\Order\Order getByUrlHashAndDomain(string $urlHash, int $domainId)
 * @method \App\Model\Order\Order getByOrderNumberAndUser(string $orderNumber, \App\Model\Customer\User\CustomerUser $customerUser)
 * @method refreshOrderItemsWithoutTransportAndPayment(\App\Model\Order\Order $order, \App\Model\Order\OrderData $orderData)
 * @method calculateOrderItemDataPrices(\App\Model\Order\Item\OrderItemData $orderItemData, int $domainId)
 * @method updateTransportAndPaymentNamesInOrderData(\App\Model\Order\OrderData $orderData, \App\Model\Order\Order $order)
 * @property \App\Model\Customer\User\CurrentCustomerUser $currentCustomerUser
 * @method setOrderPaymentStatusPageValidFromNow(\App\Model\Order\Order $order)
 * @method \App\Model\Order\Order[] getAllUnpaidGoPayOrders(\DateTime $fromDate)
 * @property \App\Model\Order\Item\OrderItemDataFactory $orderItemDataFactory
 * @property \App\Model\Order\OrderDataFactory $orderDataFactory
 * @method changeOrderPayment(\App\Model\Order\Order $order, \App\Model\Payment\Payment $payment)
 */
class OrderFacade extends BaseOrderFacade
{
    /**
     * @param int $orderId
     * @param \App\Model\Order\OrderData $orderData
     * @return \App\Model\Order\Order
     */
    #[Override]
    public function edit(int $orderId, BaseOrderData $orderData): Order
    {
        $order = $this->orderRepository->getById($orderId);
        $oldOrderStatus = $order->getStatus();

        parent::edit($orderId, $orderData);

        if ($oldOrderStatus !== $order->getStatus()) {
            $this->orderMailFacade->sendOrderStatusMailByOrder($order);
        }

        return $order;
    }

    /**
     * @param \App\Model\Transport\Type\TransportType $transportType
     * @return \App\Model\Order\Order[]
     */
    public function getAllWithoutTrackingNumberByTransportType(TransportType $transportType): array
    {
        return $this->orderRepository->getAllWithoutTrackingNumberByTransportType($transportType);
    }

    /**
     * @param \App\Model\Order\Order $order
     * @param string $trackingNumber
     */
    public function updateTrackingNumber(Order $order, string $trackingNumber): void
    {
        $order->setTrackingNumber($trackingNumber);
        $this->em->flush();
    }
}
