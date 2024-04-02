<?php

declare(strict_types=1);

namespace App\FrontendApi\Mutation\Order;

use App\FrontendApi\Model\Cart\CartFacade;
use App\FrontendApi\Model\Cart\CartWatcherFacade;
use App\FrontendApi\Model\Order\CreateOrderResult;
use App\FrontendApi\Model\Order\CreateOrderResultFactory;
use App\FrontendApi\Mutation\Order\Exception\DeprecatedFieldUserError;
use App\Model\Customer\DeliveryAddress;
use App\Model\Customer\DeliveryAddressFacade;
use App\Model\Customer\User\CustomerUser;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Validator\InputValidator;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Model\Customer\User\CurrentCustomerUser;
use Shopsys\FrameworkBundle\Model\Order\Mail\OrderMailFacade;
use Shopsys\FrameworkBundle\Model\Order\CreateOrderFacade;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessor;
use Shopsys\FrontendApiBundle\Model\Mutation\Order\CreateOrderMutation as BaseCreateOrderMutation;
use Shopsys\FrontendApiBundle\Model\Order\OrderDataFactory;

/**
 * @property \App\FrontendApi\Model\Order\OrderDataFactory $orderDataFactory
 * @property \App\Model\Order\Mail\OrderMailFacade $orderMailFacade
 * @method sendEmail(\App\Model\Order\Order $order)
 */
class CreateOrderMutation extends BaseCreateOrderMutation
{
    public const VALIDATION_GROUP_BEFORE_DEFAULT = 'beforeDefaultValidation';

    /**
     * @param \App\FrontendApi\Model\Order\OrderDataFactory $orderDataFactory
     * @param \App\Model\Order\Mail\OrderMailFacade $orderMailFacade
     * @param \App\FrontendApi\Model\Cart\CartFacade $cartFacade
     * @param \App\Model\Customer\User\CurrentCustomerUser $currentCustomerUser
     * @param \App\Model\Customer\DeliveryAddressFacade $deliveryAddressFacade
     * @param \App\FrontendApi\Model\Cart\CartWatcherFacade $cartWatcherFacade
     * @param \App\FrontendApi\Model\Order\CreateOrderResultFactory $createOrderResultFactory
     * @param \Shopsys\FrameworkBundle\Model\Order\CreateOrderFacade $newOrderFacade
     * @param \Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessor $orderProcessor
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(
        OrderDataFactory $orderDataFactory,
        OrderMailFacade $orderMailFacade,
        private readonly CartFacade $cartFacade,
        private readonly CurrentCustomerUser $currentCustomerUser,
        private readonly DeliveryAddressFacade $deliveryAddressFacade,
        private readonly CartWatcherFacade $cartWatcherFacade,
        private readonly CreateOrderResultFactory $createOrderResultFactory,
        private readonly CreateOrderFacade $newOrderFacade,
        private readonly OrderProcessor $orderProcessor,
        private readonly Domain $domain,
    ) {
        parent::__construct($orderDataFactory, $orderMailFacade);
    }

    public function createOrderMutation(Argument $argument, InputValidator $validator): CreateOrderResult
    {
        $validationGroups = $this->computeValidationGroups($argument);
        $validator->validate($validationGroups);

        $orderData = $this->orderDataFactory->createOrderDataFromArgument($argument);

        $input = $argument['input'];
        $this->handleDeprecatedFields($input);
        $cartUuid = $input['cartUuid'];
        /** @var \App\Model\Customer\User\CustomerUser|null $customerUser */
        $customerUser = $this->currentCustomerUser->findCurrentCustomerUser();
        $cart = $this->cartFacade->getCartCreateIfNotExists($customerUser, $cartUuid);

        $cartWithModifications = $this->cartWatcherFacade->getCheckedCartWithModifications($cart);

        if ($cartWithModifications->isCartModified()) {
            return $this->createOrderResultFactory->getCreateOrderResultByCartWithModifications(
                $cartWithModifications,
            );
        }

        $orderData = $this->orderProcessor->process($orderData, $cart, $this->domain->getCurrentDomainConfig());

        /** @var \App\Model\Order\Order $order */
        $order = $this->newOrderFacade->createOrder($orderData, $customerUser);

        /**
         * @todo think about how to attach more functionalities like edit customer user, subscribe to newsletter, dispatch message
         * if ($customerUser instanceof CustomerUser) {
         * $customerUserUpdateData = $this->customerUserUpdateDataFactory->createFromCustomerUser($customerUser);
         * $customerUserUpdateData->customerUserData->newsletterSubscription = $orderData->newsletterSubscription;
         * $this->customerUserFacade->editByCustomerUser($customerUser->getId(), $customerUserUpdateData);
         * $deliveryAddress = $deliveryAddress ?? $this->createDeliveryAddressForAmendingCustomerUserData($order);
         * $this->customerUserFacade->amendCustomerUserDataFromOrder($customerUser, $order, $deliveryAddress);
         * } elseif ($orderData->newsletterSubscription) {
         * $newsletterSubscriber = $this->newsletterFacade->findNewsletterSubscriberByEmailAndDomainId(
         * $orderData->email,
         * $this->domain->getId(),
         * );
         *
         * if ($newsletterSubscriber === null) {
         * $this->newsletterFacade->addSubscribedEmail($orderData->email, $this->domain->getId());
         * }
         * }
         *
         * $this->placedOrderMessageDispatcher->dispatchPlacedOrderMessage($order->getId());
         *
         *
         * /**
         * @param \App\Model\Order\Order $order
         * @return \App\Model\Customer\DeliveryAddress|null
         * /
         * private function createDeliveryAddressForAmendingCustomerUserData(Order $order): ?DeliveryAddress
         * {
         * if (
         * $order->getTransport()->isPersonalPickup() ||
         * $order->getTransport()->isPacketery() ||
         * $order->isDeliveryAddressSameAsBillingAddress()
         * ) {
         * return null;
         * }
         *
         * $deliveryAddressData = $this->deliveryAddressDataFactory->create();
         * $deliveryAddressData->firstName = $order->getDeliveryFirstName();
         * $deliveryAddressData->lastName = $order->getDeliveryLastName();
         * $deliveryAddressData->companyName = $order->getDeliveryCompanyName();
         * $deliveryAddressData->street = $order->getDeliveryStreet();
         * $deliveryAddressData->city = $order->getDeliveryCity();
         * $deliveryAddressData->postcode = $order->getDeliveryPostcode();
         * $deliveryAddressData->country = $order->getDeliveryCountry();
         * $deliveryAddressData->postcode = $order->getDeliveryPostcode();
         * $deliveryAddressData->customer = $order->getCustomerUser()->getCustomer();
         *
         * /** @var \App\Model\Customer\DeliveryAddress $deliveryAddress
         * /
        *
        $deliveryAddress = $this->deliveryAddressFactory->create($deliveryAddressData);
        *
        *
        return $deliveryAddress;
        *
    }
         */

        /* @todo uncomment
        $this->cartFacade->deleteCart($cart);
        $this->sendEmail($order);
        */

        return $this->createOrderResultFactory->getCreateOrderResultByOrder($order);
    }

    /**
     * @param string|null $deliveryAddressUuid
     * @param \App\Model\Customer\User\CustomerUser|null $customerUser
     * @return \App\Model\Customer\DeliveryAddress|null
     */
    private function resolveDeliveryAddress(
        ?string $deliveryAddressUuid,
        ?CustomerUser $customerUser,
    ): ?DeliveryAddress {
        if ($deliveryAddressUuid === null || $customerUser === null) {
            return null;
        }

        return $this->deliveryAddressFacade->findByUuidAndCustomer(
            $deliveryAddressUuid,
            $customerUser->getCustomer(),
        );
    }

    /**
     * @param array $input
     */
    private function handleDeprecatedFields(array $input): void
    {
        if (array_key_exists('products', $input) && $input['products'] !== null) {
            throw new DeprecatedFieldUserError('Usage of "products" input is deprecated, we do not work with this field anymore, the products are taken from the server cart instead.');
        }

        if (array_key_exists('transport', $input) && $input['transport'] !== null) {
            throw new DeprecatedFieldUserError('Usage of "transport" input is deprecated, we do not work with this field anymore, the transport is taken from the server cart instead.');
        }

        if (array_key_exists('payment', $input) && $input['payment'] !== null) {
            throw new DeprecatedFieldUserError('Usage of "payment" input is deprecated, we do not work with this field anymore, the payment is taken from the server cart instead.');
        }
    }

    /**
     * @param \Overblog\GraphQLBundle\Definition\Argument $argument
     * @return array|string[]
     */
    protected function computeValidationGroups(Argument $argument): array
    {
        return array_merge([self::VALIDATION_GROUP_BEFORE_DEFAULT], parent::computeValidationGroups($argument));
    }
}
