<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Transport;

use Doctrine\ORM\Mapping as ORM;
use Shopsys\FrameworkBundle\Component\Money\BetterMoney;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Component\Money\MoneyWithCurrency;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency;

/**
 * @ORM\Table(name="transport_prices")
 * @ORM\Entity
 */
class TransportPrice
{
    /**
     * @var \Shopsys\FrameworkBundle\Model\Transport\Transport
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Shopsys\FrameworkBundle\Model\Transport\Transport", inversedBy="prices")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $transport;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Money\Money
     * @ORM\Column(type="money", precision=20, scale=6)
     */
    protected $price;

    /**
     * @ORM\Embedded(class="Shopsys\FrameworkBundle\Component\Money\MoneyWithCurrency")
     */
    protected $priceWithCurrency;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    protected $domainId;

    /**
     * @param \Shopsys\FrameworkBundle\Model\Transport\Transport $transport
     * @param \Shopsys\FrameworkBundle\Component\Money\Money $price
     * @param int $domainId
     */
    public function __construct(Transport $transport, Money $price, int $domainId, Currency $currency)
    {
        $this->transport = $transport;
        $this->price = $price;
        $this->domainId = $domainId;
        $this->setPriceWithCurrency(BetterMoney::create($price->getAmount(), $currency));
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Transport\Transport
     */
    public function getTransport(): Transport
    {
        return $this->transport;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Component\Money\Money
     */
    public function getPrice(): Money
    {
        return $this->price;
    }

    /**
     * @param \Shopsys\FrameworkBundle\Component\Money\Money $price
     */
    public function setPrice(Money $price): void
    {
        $this->price = $price;
    }

    public function setPriceWithCurrency(BetterMoney $money): void
    {
        $this->priceWithCurrency = new MoneyWithCurrency($money->getAmount(), $money->getCurrency());
    }

    /**
     * @param int $domainId
     */
    public function setDomainId(int $domainId): void
    {
        $this->domainId = $domainId;
    }

    /**
     * @return int
     */
    public function getDomainId(): int
    {
        return $this->domainId;
    }
}
