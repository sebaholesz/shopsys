<?php

declare(strict_types=1);

namespace App\FrontendApi\Model\Order;

use App\FrontendApi\Model\Order\Exception\InvalidPacketeryAddressIdUserError;
use App\FrontendApi\Resolver\Store\Exception\StoreNotFoundUserError;
use App\Model\Cart\Cart;
use App\Model\Order\OrderData;
use Overblog\GraphQLBundle\Definition\Argument;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Country\CountryFacade;
use Shopsys\FrameworkBundle\Model\Order\OrderData as BaseOrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderDataFactory as FrameworkOrderDataFactory;
use Shopsys\FrameworkBundle\Model\Payment\PaymentFacade;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Shopsys\FrameworkBundle\Model\Product\ProductFacade;
use Shopsys\FrameworkBundle\Model\Store\Exception\StoreByUuidNotFoundException;
use Shopsys\FrameworkBundle\Model\Store\Store;
use Shopsys\FrameworkBundle\Model\Store\StoreFacade;
use Shopsys\FrameworkBundle\Model\Transport\TransportFacade;
use Shopsys\FrontendApiBundle\Model\Order\OrderDataFactory as BaseOrderDataFactory;

/**
 * @property \App\Model\Payment\PaymentFacade $paymentFacade
 * @property \App\Model\Transport\TransportFacade $transportFacade
 * @property \App\Model\Order\OrderDataFactory $orderDataFactory
 * @property \App\Model\Product\ProductFacade $productFacade
 */
class OrderDataFactory extends BaseOrderDataFactory
{
    /**
     * @param \Overblog\GraphQLBundle\Definition\Argument $argument
     * @return \App\Model\Order\OrderData
     */
    public function createOrderDataFromArgument(Argument $argument): OrderData
    {
        /** @var \App\Model\Order\OrderData $orderData */
        $orderData = parent::createOrderDataFromArgument($argument);

        $input = $argument['input'];
        $orderData->isCompanyCustomer = $input['onCompanyBehalf'];
        $orderData->newsletterSubscription = $input['newsletterSubscription'];

        return $orderData;
    }

    /**
     * @param array $input
     * @param \App\Model\Order\OrderData $orderData
     * @return \App\Model\Order\OrderData
     */
    protected function withResolvedFields(array $input, BaseOrderData $orderData): OrderData
    {
        /** @var \App\Model\Order\OrderData $cloneOrderData */
        $cloneOrderData = clone $orderData;

        $cloneOrderData->currency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($this->domain->getId());

        $cloneOrderData->country = $this->countryFacade->findByCode($input['country']);

        if ($input['differentDeliveryAddress']) {
            $cloneOrderData->deliveryCountry = $this->countryFacade->findByCode($input['deliveryCountry']);
        }

        unset($input['currency'], $input['country'], $input['deliveryCountry']);

        foreach ($input as $key => $value) {
            if (property_exists(get_class($orderData), $key)) {
                $cloneOrderData->{$key} = $value;
            }
        }

        return $cloneOrderData;
    }
}
