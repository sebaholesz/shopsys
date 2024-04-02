<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Order;

use Doctrine\ORM\EntityManagerInterface;
use Shopsys\FrameworkBundle\Model\Administrator\Security\AdministratorFrontSecurityFacade;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItem;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemDataFactory;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemFactory;
use Shopsys\FrameworkBundle\Model\Order\Status\OrderStatusRepository;

class CreateOrderFacade
{
    public function __construct(
        protected readonly OrderStatusRepository $orderStatusRepository,
        protected readonly OrderNumberSequenceRepository $orderNumberSequenceRepository,
        protected readonly OrderHashGeneratorRepository $orderHashGeneratorRepository,
        protected readonly OrderFactory $orderFactory,
        protected readonly EntityManagerInterface $em,
        protected readonly OrderPriceCalculation $orderPriceCalculation,
        protected readonly AdministratorFrontSecurityFacade $administratorFrontSecurityFacade,
        protected readonly OrderItemFactory $orderItemFactory,
        protected readonly OrderItemDataFactory $orderItemDataFactory,
    ) {
    }

    public function createOrder(
        OrderData $orderData,
        ?CustomerUser $customerUser,
    ): Order {
        /*
         * @TODO add promo codes
         foreach ($orderData->appliedPromoCodes as $promoCode) {
            $promoCode->decreaseRemainingUses();
        }*/

        if ($orderData->status === null) {
            /** @var \App\Model\Order\Status\OrderStatus $status */
            $status = $this->orderStatusRepository->getDefault();
            $orderData->status = $status;
        }

        $orderNumber = (string)$this->orderNumberSequenceRepository->getNextNumber();
        $orderUrlHash = $this->orderHashGeneratorRepository->getUniqueHash();

        $this->setOrderDataAdministrator($orderData);

        $order = $this->orderFactory->create(
            $orderData,
            $orderNumber,
            $orderUrlHash,
            $customerUser,
        );

        $this->em->persist($order);

        $this->fillOrderItems($order, $orderData);

        $order->setTotalPrices($orderData->totalPrice, $orderData->totalPriceByItemType[OrderItem::TYPE_PRODUCT]);

        $this->em->flush();

        return $order;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     */
    protected function setOrderDataAdministrator(OrderData $orderData): void
    {
        /** app implementation. Uncomment after moving of loginAsUserFacade */
        //$currentAdministratorLoggedAsCustomer = $this->loginAsUserFacade->getCurrentAdministratorLoggedAsCustomer();
        $currentAdministratorLoggedAsCustomer = null;

        if ($currentAdministratorLoggedAsCustomer === null) {
            return;
        }

        $orderData->createdAsAdministrator = $currentAdministratorLoggedAsCustomer;
        $orderData->createdAsAdministratorName = $currentAdministratorLoggedAsCustomer->getRealName();
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     */
    protected function fillOrderItems(Order $order, OrderData $orderData): void
    {
        $this->fillOrderProducts($order, $orderData);
        $this->fillOrderPayment($order, $orderData);
        $this->fillOrderTransport($order, $orderData);
        $this->fillOrderRounding($order, $orderData);
    }

    protected function fillOrderProducts(Order $order, OrderData $orderData): void
    {
        foreach ($orderData->getItemsByType(OrderItem::TYPE_PRODUCT) as $orderItemData) {
            $orderItem = $this->orderItemFactory->createProduct(
                $orderItemData,
                $order,
                $orderItemData->product,
            );

            $this->em->persist($orderItem);
        }

        // @todo add promo codes

        return;


        // original app implementation
        $quantifiedItemPrices = $orderPreview->getQuantifiedItemsPrices();
        $quantifiedItemDiscounts = $orderPreview->getQuantifiedItemsDiscounts();

        foreach ($orderPreview->getQuantifiedProducts() as $index => $quantifiedProduct) {
            /** @var \App\Model\Product\Product $product */
            $product = $quantifiedProduct->getProduct();

            $quantifiedItemPrice = $quantifiedItemPrices[$index];
            /** @var \Shopsys\FrameworkBundle\Model\Pricing\Price|null $quantifiedItemDiscount */
            $quantifiedItemDiscount = $quantifiedItemDiscounts[$index];

            $orderItemData = $this->orderItemDataFactory->create();
            $orderItemData->name = $product->getFullname($locale);
            $orderItemData->priceWithoutVat = $quantifiedItemPrice->getUnitPrice()->getPriceWithoutVat();
            $orderItemData->priceWithVat = $quantifiedItemPrice->getUnitPrice()->getPriceWithVat();
            $orderItemData->vatPercent = $product->getVatForDomain($order->getDomainId())->getPercent();
            $orderItemData->quantity = $quantifiedProduct->getQuantity();
            $orderItemData->unitName = $product->getUnit()->getName($locale);
            $orderItemData->catnum = $product->getCatnum();

            $orderItem = $this->orderItemFactory->createProduct(
                $orderItemData,
                $order,
                $product,
            );

            $this->em->persist($orderItem);

            if ($quantifiedItemDiscount === null) {
                continue;
            }

            $coupon = $this->addOrderItemDiscountAndReturnIt(
                $orderItem,
                $quantifiedItemDiscount,
                $locale,
                (float)$orderPreview->getPromoCodeDiscountPercent(),
                $orderPreview->getPromoCodeIdentifier(),
            );
            $orderItem->setRelatedOrderItem($coupon);

            $this->em->persist($coupon);
        }
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     */
    protected function fillOrderPayment(Order $order, OrderData $orderData): void
    {
        $payment = $orderData->payment;

        $orderPaymentsData = $orderData->getItemsByType(OrderItem::TYPE_PAYMENT);

        foreach ($orderPaymentsData as $orderPaymentData) {
            $orderPayment = $this->orderItemFactory->createPayment(
                $orderPaymentData,
                $order,
                $payment,
            );

            $this->em->persist($orderPayment);
        }
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     */
    protected function fillOrderTransport(Order $order, OrderData $orderData): void
    {
        $transport = $orderData->transport;

        $orderTransportsData = $orderData->getItemsByType(OrderItem::TYPE_TRANSPORT);

        foreach ($orderTransportsData as $orderTransportData) {
            $orderTransport = $this->orderItemFactory->createTransport(
                $orderTransportData,
                $order,
                $transport,
            );

            $this->em->persist($orderTransport);
        }
    }

    /**
     * @param \Shopsys\FrameworkBundle\Model\Order\Order $order
     * @param \Shopsys\FrameworkBundle\Model\Order\OrderData $orderData
     */
    protected function fillOrderRounding(Order $order, OrderData $orderData): void
    {
        $orderRoundingsData = $orderData->getItemsByType(OrderItem::TYPE_ROUNDING);

        foreach ($orderRoundingsData as $orderRoundingData) {
            $orderRounding = $this->orderItemFactory->createRounding(
                $orderRoundingData,
                $order,
            );

            $this->em->persist($orderRounding);
        }
    }


    /* ####################### original implementation from app ################### */

    /**
     * @param \App\Model\Order\Item\OrderItem $orderItem
     * @param \Shopsys\FrameworkBundle\Model\Pricing\Price $quantifiedItemDiscount
     * @param string $locale
     * @param float $discountPercent
     * @param string|null $promoCodeIdentifier
     * @return \App\Model\Order\Item\OrderItem
     */
    private function addOrderItemDiscountAndReturnIt(
        OrderItem $orderItem,
        Price $quantifiedItemDiscount,
        string $locale,
        float $discountPercent,
        ?string $promoCodeIdentifier = null,
    ): Item\OrderItem {
        $name = sprintf(
            '%s %s - %s',
            t('Promo code', [], Translator::DEFAULT_TRANSLATION_DOMAIN, $locale),
            $this->numberFormatterExtension->formatPercent(-$discountPercent, $locale),
            $orderItem->getName(),
        );
        $discountPrice = $quantifiedItemDiscount->inverse();

        $orderItemData = $this->orderItemDataFactory->create();
        $orderItemData->name = $name;
        $orderItemData->priceWithoutVat = $discountPrice->getPriceWithoutVat();
        $orderItemData->priceWithVat = $discountPrice->getPriceWithVat();
        $orderItemData->vatPercent = $orderItem->getVatPercent();
        $orderItemData->quantity = 1;
        $orderItemData->promoCodeIdentifier = $promoCodeIdentifier;
        $orderItemData->relatedOrderItem = $orderItem;

        return $this->orderItemFactory->createDiscount(
            $orderItemData,
            $orderItem->getOrder(),
        );
    }

}
