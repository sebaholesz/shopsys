<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Command;

use App\DataFixtures\Demo\CountryDataFixture;
use App\DataFixtures\Demo\OrderStatusDataFixture;
use App\DataFixtures\Demo\PaymentDataFixture;
use App\DataFixtures\Demo\ProductDataFixture;
use App\DataFixtures\Demo\TransportDataFixture;
use App\Model\Cart\Cart;
use App\Model\Cart\Item\CartItem;
use App\Model\Cart\Payment\CartPaymentData;
use App\Model\Cart\Transport\CartTransportData;
use App\Model\Order\Order;
use App\Model\Order\Status\OrderStatus;
use App\Model\Payment\Payment;
use App\Model\Product\Product;
use App\Model\Transport\Transport;
use DateTime;
use Ramsey\Uuid\Uuid;
use Shopsys\FrameworkBundle\Component\DataFixture\PersistentReferenceFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Cart\CartFacade;
use Shopsys\FrameworkBundle\Model\Country\Country;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUser;
use Shopsys\FrameworkBundle\Model\Customer\User\CustomerUserRepository;
use Shopsys\FrameworkBundle\Model\Order\CreateOrderFacade;
use Shopsys\FrameworkBundle\Model\Order\OrderData;
use Shopsys\FrameworkBundle\Model\Order\OrderDataFactory;
use Shopsys\FrameworkBundle\Model\Order\Processing\OrderProcessor;
use Shopsys\FrameworkBundle\Model\Pricing\Currency\CurrencyFacade;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'shopsys:order:test')]
class TestOrderProcessCommand extends Command
{
    public function __construct(
        private readonly CartFacade $cartFacade,
        private readonly OrderProcessor $orderProcessor,
        private readonly Domain $domain,
        private readonly CreateOrderFacade $createOrderFacade,
        private readonly CustomerUserRepository $customerUserRepository,
        private readonly OrderDataFactory $orderDataFactory,
        private readonly PersistentReferenceFacade $persistentReferenceFacade,
        private readonly CurrencyFacade $currencyFacade,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Test order process');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute2(InputInterface $input, OutputInterface $output): int
    {
        $cart = $this->cartFacade->findCartByCartIdentifier('524ca58c-bffa-40ee-8365-c09c8cd4bc72');
        $domainConfig = $this->domain->getDomainConfigById(1);

        if ($cart === null) {
            $output->writeln('Cart not found');

            return Command::FAILURE;
        }

        $orderData = new OrderData();

        $orderData = $this->orderProcessor->process($orderData, $cart, $domainConfig);

        d($orderData);

        $this->createOrderFacade->createOrder(
            $orderData,
            $cart->getCustomerUser(),
        );

        return Command::SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $domainConfig = $this->domain->getDomainConfigById(1);
        $domainId = 1;
        $domainDefaultCurrency = $this->currencyFacade->getDomainDefaultCurrencyByDomainId($domainId);

        /** @var \App\Model\Customer\User\CustomerUser $customerUser */
        $customerUser = $this->customerUserRepository->findCustomerUserByEmailAndDomain(
            'no-reply@shopsys.com',
            $domainId,
        );
        $orderData = $this->orderDataFactory->create();
        $orderData->transport = $this->getReference(TransportDataFixture::TRANSPORT_PERSONAL, Transport::class);
        $orderData->payment = $this->getReference(PaymentDataFixture::PAYMENT_GOPAY_DOMAIN . $domainId, Payment::class);
        $orderData->status = $this->getReference(OrderStatusDataFixture::ORDER_STATUS_DONE, OrderStatus::class);
        $orderData->firstName = 'Jiří';
        $orderData->lastName = 'Ševčík';
        $orderData->email = 'no-reply@shopsys.com';
        $orderData->telephone = '+420369554147';
        $orderData->street = 'První 1';
        $orderData->city = 'Ostrava';
        $orderData->postcode = '71200';
        $orderData->country = $this->getReference(CountryDataFixture::COUNTRY_CZECH_REPUBLIC, Country::class);
        $orderData->deliveryAddressSameAsBillingAddress = true;
        $orderData->domainId = $domainId;
        $orderData->currency = $domainDefaultCurrency;
        $orderData->createdAt = (new DateTime('now -3 day'))->setTime(12, 40, 22);
        $order = $this->createOrder(
            $orderData,
            [
                ProductDataFixture::PRODUCT_PREFIX . '9' => 2,
                ProductDataFixture::PRODUCT_PREFIX . '10' => 3,
            ],
            $customerUser,
        );

        return Command::SUCCESS;
    }


    /**
     * @template T
     * @param string $name
     * @param class-string<T>|null $entityClassName
     * @return T
     */
    public function getReference($name, ?string $entityClassName = null)
    {
        return $this->persistentReferenceFacade->getReference($name, $entityClassName);
    }

    /**
     * @param \App\Model\Order\OrderData $orderData
     * @param array<string, int> $products
     * @param \App\Model\Customer\User\CustomerUser|null $customerUser
     * @return \App\Model\Order\Order
     */
    private function createOrder(
        OrderData $orderData,
        array $products,
        ?CustomerUser $customerUser = null,
    ): Order {
        $cart = new Cart('cartIdentifier', $customerUser);

        foreach ($products as $productReferenceName => $quantity) {
            $product = $this->getReference($productReferenceName, Product::class);
            $cart->addItem(new CartItem($cart, $product, $quantity, Money::zero()));
        }

        $cartPaymentData = new CartPaymentData();
        $cartPaymentData->payment = $orderData->payment;
        $cartPaymentData->watchedPrice = Money::zero();
        $cartPaymentData->goPayBankSwift = null;
        $cart->editCartPayment($cartPaymentData);

        $cartTransportData = new CartTransportData();
        $cartTransportData->transport = $orderData->transport;
        $cartTransportData->watchedPrice = Money::zero();
        $cartTransportData->pickupPlaceIdentifier = null;
        $cart->editCartTransport($cartTransportData);

        $orderData->uuid = Uuid::uuid4()->toString();

        $orderData = $this->orderProcessor->process($orderData, $cart, $this->domain->getDomainConfigById($orderData->domainId));

        /** @var \App\Model\Order\Order $order */
        $order = $this->createOrderFacade->createOrder($orderData, $customerUser);

        return $order;
    }

}
