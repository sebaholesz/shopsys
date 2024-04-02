<?php

declare(strict_types=1);

namespace App\FrontendApi\Model\Cart;

use App\FrontendApi\Model\Payment\Exception\PaymentPriceChangedException;
use App\FrontendApi\Model\Payment\PaymentValidationFacade;
use App\FrontendApi\Model\Transport\Exception\TransportPriceChangedException;
use App\FrontendApi\Model\Transport\Exception\TransportWeightLimitExceededException;
use App\FrontendApi\Model\Transport\TransportValidationFacade;
use App\Model\Cart\Cart;
use App\Model\Cart\Payment\CartPaymentFacade;
use App\Model\Cart\Transport\CartTransportFacade;
use App\Model\Order\Item\OrderItem;
use App\Model\Order\OrderDataFactory;
use App\Model\Payment\Payment;
use App\Model\Payment\PaymentFacade;
use App\Model\Transport\Transport;
use App\Model\Transport\TransportFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Order\OrderData;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessor;
use Shopsys\FrameworkBundle\Model\Pricing\Price;
use Shopsys\FrameworkBundle\Model\Store\Exception\StoreByUuidNotFoundException;
use Shopsys\FrameworkBundle\Model\TransportAndPayment\FreeTransportAndPaymentFacade;

class TransportAndPaymentWatcherFacade
{
    private CartWithModificationsResult $cartWithModificationsResult;

    /**
     * @param \App\Model\Transport\TransportFacade $transportFacade
     * @param \App\Model\Payment\PaymentFacade $paymentFacade
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     * @param \Shopsys\FrameworkBundle\Model\TransportAndPayment\FreeTransportAndPaymentFacade $freeTransportAndPaymentFacade
     * @param \App\Model\Cart\Transport\CartTransportFacade $cartTransportFacade
     * @param \App\FrontendApi\Model\Transport\TransportValidationFacade $transportValidationFacade
     * @param \App\Model\Cart\Payment\CartPaymentFacade $cartPaymentFacade
     * @param \App\FrontendApi\Model\Payment\PaymentValidationFacade $paymentValidationFacade
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessor $orderProcessor
     * @param \App\Model\Order\OrderDataFactory $orderDataFactory
     */
    public function __construct(
        private readonly TransportFacade $transportFacade,
        private readonly PaymentFacade $paymentFacade,
        private readonly Domain $domain,
        private readonly FreeTransportAndPaymentFacade $freeTransportAndPaymentFacade,
        private readonly CartTransportFacade $cartTransportFacade,
        private readonly TransportValidationFacade $transportValidationFacade,
        private readonly CartPaymentFacade $cartPaymentFacade,
        private readonly PaymentValidationFacade $paymentValidationFacade,
        private readonly OrderProcessor $orderProcessor,
        private readonly OrderDataFactory $orderDataFactory,
    ) {
    }

    /**
     * @param \App\FrontendApi\Model\Cart\CartWithModificationsResult $cartWithModificationsResult
     * @param \App\Model\Cart\Cart $cart
     * @return \App\FrontendApi\Model\Cart\CartWithModificationsResult
     */
    public function checkTransportAndPayment(
        CartWithModificationsResult $cartWithModificationsResult,
        Cart $cart,
    ): CartWithModificationsResult {
        $this->cartWithModificationsResult = $cartWithModificationsResult;

        $domainId = $this->domain->getId();

        $orderData = $this->orderDataFactory->create();
        $orderData = $this->orderProcessor->process($orderData, $cart, $this->domain->getCurrentDomainConfig());

        $productsPrice = $orderData->totalPriceByItemType[OrderItem::TYPE_PRODUCT];

        if ($this->freeTransportAndPaymentFacade->isActive($domainId)) {
            $amountWithVatForFreeTransport = $this->freeTransportAndPaymentFacade->getRemainingPriceWithVat(
                $productsPrice->getPriceWithVat(),
                $domainId,
            );

            $this->cartWithModificationsResult->setRemainingAmountWithVatForFreeTransport($amountWithVatForFreeTransport);
        }

        $this->cartWithModificationsResult->setTotalPrice($orderData->totalPrice);
        $this->cartWithModificationsResult->setTotalItemsPrice($productsPrice);
        $this->cartWithModificationsResult->setTotalDiscountPrice($orderData->totalPriceByItemType[OrderItem::TYPE_DISCOUNT]);
        $this->cartWithModificationsResult->setTotalPriceWithoutDiscountTransportAndPayment(
            $this->calculatePriceWithoutDiscountTransportAndPayment($orderData)
        );
        $this->cartWithModificationsResult->setRoundingPrice($orderData->totalPriceByItemType[OrderItem::TYPE_ROUNDING]);

        $this->checkTransport($cart);
        $this->checkPayment($cart);

        return $this->cartWithModificationsResult;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     * @return \Shopsys\FrameworkBundle\Model\Pricing\Price
     */
    private function calculatePriceWithoutDiscountTransportAndPayment(OrderData $orderData): Price
    {
        return $orderData->totalPrice
            ->subtract($orderData->totalPriceByItemType[OrderItem::TYPE_TRANSPORT])
            ->subtract($orderData->totalPriceByItemType[OrderItem::TYPE_PAYMENT])
            ->subtract($orderData->totalPriceByItemType[OrderItem::TYPE_DISCOUNT]);
    }

    /**
     * @param \App\Model\Transport\Transport $transport
     * @param \App\Model\Cart\Cart $cart
     */
    private function checkTransportPrice(Transport $transport, Cart $cart): void
    {
        try {
            $this->transportValidationFacade->checkTransportPrice($transport, $cart);
        } catch (TransportPriceChangedException $exception) {
            $this->cartWithModificationsResult->setTransportPriceChanged(true);
            $this->cartTransportFacade->setTransportWatchedPrice($cart, $exception->getCurrentTransportPrice()->getPriceWithVat());
        }
    }

    /**
     * @param \App\Model\Cart\Cart $cart
     * @param \App\Model\Payment\Payment $payment
     */
    private function checkPaymentPrice(Cart $cart, Payment $payment): void
    {
        try {
            $this->paymentValidationFacade->checkPaymentPrice($payment, $cart);
        } catch (PaymentPriceChangedException $exception) {
            $this->cartWithModificationsResult->setPaymentPriceChanged(true);
            $this->cartPaymentFacade->setPaymentWatchedPrice($cart, $exception->getCurrentPaymentPrice()->getPriceWithVat());
        }
    }

    /**
     * @param \App\Model\Transport\Transport $transport
     * @param \App\Model\Cart\Cart $cart
     */
    private function checkTransportWeightLimit(Transport $transport, Cart $cart): void
    {
        try {
            $this->transportValidationFacade->checkTransportWeightLimit($transport, $cart);
        } catch (TransportWeightLimitExceededException) {
            $this->cartWithModificationsResult->setTransportWeightLimitExceeded(true);
            $this->cartTransportFacade->unsetCartTransport($cart);
        }
    }

    /**
     * @param \App\Model\Transport\Transport $transport
     * @param \App\Model\Cart\Cart $cart
     */
    private function checkPersonalPickupStoreAvailability(Transport $transport, Cart $cart): void
    {
        try {
            $this->transportValidationFacade->checkPersonalPickupStoreAvailability($transport, $cart->getPickupPlaceIdentifier());
        } catch (StoreByUuidNotFoundException) {
            $this->cartWithModificationsResult->setPersonalPickupStoreUnavailable(true);
            $this->cartTransportFacade->unsetPickupPlaceIdentifierFromCart($cart);
        }
    }

    /**
     * @param \App\Model\Cart\Cart $cart
     */
    private function checkTransport(Cart $cart): void
    {
        if ($cart->isEmpty()) {
            $this->cartTransportFacade->unsetCartTransport($cart);
        }


        $transport = $cart->getTransport();

        if ($transport === null) {
            if ($cart->getTransportWatchedPrice() !== null) {
                // this might happen when transport is set to null in cart thanks to "onDelete=SET NULL" ORM setting
                $this->setTransportInCartUnavailable($cart);
            }

            return;
        }

        if ($this->transportFacade->isTransportVisibleAndEnabledOnCurrentDomain($transport) === false) {
            $this->setTransportInCartUnavailable($cart);

            return;
        }
        $this->checkTransportPrice($transport, $cart);
        $this->checkTransportWeightLimit($transport, $cart);
        $this->checkPersonalPickupStoreAvailability($transport, $cart);
    }

    /**
     * @param \App\Model\Cart\Cart $cart
     */
    private function checkPayment(Cart $cart): void
    {
        if ($cart->isEmpty()) {
            $this->cartPaymentFacade->unsetCartPayment($cart);
        }

        $payment = $cart->getPayment();

        if ($payment === null) {
            if ($cart->getPaymentWatchedPrice() !== null) {
                // this might happen when payment is set to null in cart thanks to "onDelete=SET NULL" ORM setting
                $this->setPaymentInCartUnavailable($cart);
            }

            return;
        }

        if ($this->paymentFacade->isPaymentVisibleAndEnabledOnCurrentDomain($payment) === false) {
            $this->setPaymentInCartUnavailable($cart);

            return;
        }
        $this->checkPaymentPrice($cart, $payment);
    }

    /**
     * @param \App\Model\Cart\Cart $cart
     */
    private function setTransportInCartUnavailable(Cart $cart): void
    {
        $this->cartWithModificationsResult->setTransportIsUnavailable();
        $this->cartTransportFacade->unsetCartTransport($cart);
    }

    /**
     * @param \App\Model\Cart\Cart $cart
     */
    private function setPaymentInCartUnavailable(Cart $cart): void
    {
        $this->cartWithModificationsResult->setPaymentIsUnavailable();
        $this->cartPaymentFacade->unsetCartPayment($cart);
    }
}
