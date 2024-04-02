<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Order;

use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItem;
use Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData;
use Shopsys\FrameworkBundle\Model\Pricing\Price;

class OrderData
{
    public const NEW_ITEM_PREFIX = 'new_';

    /**
     * @var string|null
     */
    public $uuid;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\Transport|null
     */
    public $transport;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\Payment|null
     */
    public $payment;

    /**
     * @var string|null
     */
    public $orderNumber;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\Status\OrderStatus|null
     */
    public $status;

    /**
     * @var string|null
     */
    public $firstName;

    /**
     * @var string|null
     */
    public $lastName;

    /**
     * @var string|null
     */
    public $email;

    /**
     * @var string|null
     */
    public $telephone;

    /**
     * @var string|null
     */
    public $companyName;

    /**
     * @var string|null
     */
    public $companyNumber;

    /**
     * @var string|null
     */
    public $companyTaxNumber;

    /**
     * @var string|null
     */
    public $street;

    /**
     * @var string|null
     */
    public $city;

    /**
     * @var string|null
     */
    public $postcode;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Country\Country|null
     */
    public $country;

    /**
     * @var bool
     */
    public $deliveryAddressSameAsBillingAddress;

    /**
     * @var string|null
     */
    public $deliveryFirstName;

    /**
     * @var string|null
     */
    public $deliveryLastName;

    /**
     * @var string|null
     */
    public $deliveryCompanyName;

    /**
     * @var string|null
     */
    public $deliveryTelephone;

    /**
     * @var string|null
     */
    public $deliveryStreet;

    /**
     * @var string|null
     */
    public $deliveryCity;

    /**
     * @var string|null
     */
    public $deliveryPostcode;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Country\Country|null
     */
    public $deliveryCountry;

    /**
     * @var string|null
     */
    public $note;

    /**
     * @deprecated
     * @var \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData[]
     */
    public $itemsWithoutTransportAndPayment;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData[]
     */
    public $items = [];

    /**
     * @var \DateTime|null
     */
    public $createdAt;

    /**
     * @var int|null
     */
    public $domainId;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency|null
     */
    public $currency;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Administrator\Administrator|null
     */
    public $createdAsAdministrator;

    /**
     * @var string|null
     */
    public $createdAsAdministratorName;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData|null
     */
    public $orderPayment;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData|null
     */
    public $orderTransport;

    /**
     * @var string|null
     */
    public $origin;

    /**
     * @var string|null
     */
    public $goPayBankSwift;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Payment\Transaction\Refund\PaymentTransactionRefundData[]
     */
    public $paymentTransactionRefunds;

    public Price $totalPrice;

    /**
     * @var array<string, \Shopsys\FrameworkBundle\Model\Pricing\Price>
     */
    public array $totalPriceByItemType = [];
    /**
     * @var string|null
     */
    public ?string $pickupPlaceIdentifier;
    /**
     * @var \Shopsys\FrameworkBundle\Model\Store\Store|null
     */
    public $personalPickupStore;

    public function __construct()
    {
        $this->itemsWithoutTransportAndPayment = [];
        $this->deliveryAddressSameAsBillingAddress = false;
        $this->paymentTransactionRefunds = [];

        $this->totalPrice = new Price(Money::zero(), Money::zero());

        $this->setZeroPricesForAllTypes();
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData[]
     */
    public function getNewItemsWithoutTransportAndPayment()
    {
        $newItemsWithoutTransportAndPayment = [];

        foreach ($this->itemsWithoutTransportAndPayment as $index => $item) {
            if (str_starts_with((string)$index, self::NEW_ITEM_PREFIX)) {
                $newItemsWithoutTransportAndPayment[] = $item;
            }
        }

        return $newItemsWithoutTransportAndPayment;
    }

    protected function setZeroPricesForAllTypes(): void
    {
        $this->totalPriceByItemType[OrderItem::TYPE_PRODUCT] = new Price(Money::zero(), Money::zero());
        $this->totalPriceByItemType[OrderItem::TYPE_DISCOUNT] = new Price(Money::zero(), Money::zero());
        $this->totalPriceByItemType[OrderItem::TYPE_PAYMENT] = new Price(Money::zero(), Money::zero());
        $this->totalPriceByItemType[OrderItem::TYPE_TRANSPORT] = new Price(Money::zero(), Money::zero());
        $this->totalPriceByItemType[OrderItem::TYPE_ROUNDING] = new Price(Money::zero(), Money::zero());
    }

    /**
     * @param string $type
     * @return \Shopsys\FrameworkBundle\Model\Order\Item\OrderItemData[]
     */

    public function getItemsByType(string $type): array
    {
        return array_filter(
            $this->items,
            fn (OrderItemData $item) => $item->type === $type,
        );
    }

    /**
     * @todo
     * add methods for add totalprice
     * update type total price
     * add item
     * maybe add prices by type – So I am able to know how much I have to pay for products, transport, payment, etc.
     */
}
