<?php

declare(strict_types=1);

namespace App\Model\Order\Processing\OrderProcessorMiddleware;

use App\FrontendApi\Model\Order\Exception\InvalidPacketeryAddressIdUserError;
use App\FrontendApi\Resolver\Store\Exception\StoreNotFoundUserError;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItem;
use Shopsys\FrameworkBundle\Model\Order\OrderData;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessorMiddleware\OrderProcessorMiddlewareInterface;
use Shopsys\FrameworkBundle\Model\Store\Exception\StoreByUuidNotFoundException;
use Shopsys\FrameworkBundle\Model\Store\Store;
use Shopsys\FrameworkBundle\Model\Store\StoreFacade;

class PersonalPickupPointMiddleware implements OrderProcessorMiddlewareInterface
{
    public function __construct(
        private readonly StoreFacade $storeFacade,
    ) {
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData $orderProcessingData
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingStackInterface $orderProcessingStack
     * @return \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessingData
     */
    public function handle(OrderProcessingData $orderProcessingData, OrderProcessingStackInterface $orderProcessingStack): OrderProcessingData
    {
        /** @var \App\Model\Cart\Cart $cart */
        $cart = $orderProcessingData->cart;
        $pickupPlaceIdentifier = $cart->getPickupPlaceIdentifier();

        if ($pickupPlaceIdentifier === null) {
            return $orderProcessingStack->next()->handle($orderProcessingData, $orderProcessingStack);
        }

        $orderData = $orderProcessingData->orderData;

        /** @var \App\Model\Transport\Transport $transport */
        $transport = $orderData->transport;

        if ($transport->isPersonalPickup()) {
            try {
                $store = $this->storeFacade->getByUuidAndDomainId(
                    $pickupPlaceIdentifier,
                    $orderProcessingData->domainConfig->getId(),
                );

                $orderData->getItemsByType(OrderItem::TYPE_TRANSPORT)[0]->name .= ' ' . $store->getName();

                $this->updateDeliveryDataByStore($orderData, $store);
            } catch (StoreByUuidNotFoundException $exception) {
                /** @todo should middleware throw user errors? */
                throw new StoreNotFoundUserError($exception->getMessage());
            }
        } elseif (
            $transport->isPacketery() &&
            $this->isPickupPlaceIdentifierIntegerInString($pickupPlaceIdentifier)
        ) {
            /** @todo should middleware throw user errors? */
            throw new InvalidPacketeryAddressIdUserError('Wrong packetery address ID');
        }

        $orderData->pickupPlaceIdentifier = $pickupPlaceIdentifier;


        return $orderProcessingStack->next()->handle($orderProcessingData, $orderProcessingStack);
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     * @param \Shopsys\FrameworkBundle\Model\Store\Store $store
     */
    private function updateDeliveryDataByStore(OrderData $orderData, Store $store): void
    {
        $orderData->personalPickupStore = $store;
        $orderData->deliveryAddressSameAsBillingAddress = false;

        $orderData->deliveryFirstName = $orderData->deliveryFirstName ?? $orderData->firstName;
        $orderData->deliveryLastName = $orderData->deliveryLastName ?? $orderData->lastName;
        $orderData->deliveryCompanyName = $orderData->deliveryCompanyName ?? $orderData->companyName;
        $orderData->deliveryTelephone = $orderData->deliveryTelephone ?? $orderData->telephone;

        $orderData->deliveryStreet = $store->getStreet();
        $orderData->deliveryCity = $store->getCity();
        $orderData->deliveryPostcode = $store->getPostcode();
        $orderData->deliveryCountry = $store->getCountry();
    }

    /**
     * @param string $pickupPlaceIdentifier
     * @return bool
     */
    private function isPickupPlaceIdentifierIntegerInString(string $pickupPlaceIdentifier): bool
    {
        return (string)(int)$pickupPlaceIdentifier !== $pickupPlaceIdentifier;
    }
}
