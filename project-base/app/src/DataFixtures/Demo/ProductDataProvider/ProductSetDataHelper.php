<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo\ProductDataProvider;

use App\DataFixtures\Demo\CurrencyDataFixture;
use App\DataFixtures\Demo\VatDataFixture;
use App\Model\Product\Product;
use App\Model\Product\ProductData;
use App\Model\Product\ProductDataFactory;
use Shopsys\FrameworkBundle\Component\DataFixture\PersistentReferenceFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Money\Money;
use Shopsys\FrameworkBundle\Model\Pricing\Group\PricingGroupFacade;
use Shopsys\FrameworkBundle\Model\Pricing\PriceConverter;
use Shopsys\FrameworkBundle\Model\Stock\ProductStockDataFactory;
use Shopsys\FrameworkBundle\Model\Stock\StockRepository;

class ProductSetDataHelper
{
    public function __construct(
        private readonly Domain $domain,
        private readonly UspsDataProvider $uspsDataProvider,
        private readonly VatDataProvider $vatDataProvider,
        private readonly ProductDataFactory $productDataFactory,
        private readonly PersistentReferenceFacade $persistentReferenceFacade,
        private readonly PricingGroupFacade $pricingGroupFacade,
        private readonly PriceConverter $priceConverter,
        private readonly StockRepository $stockRepository,
        private readonly ProductStockDataFactory $productStockDataFactory,
    ) {
    }

    public function createProductData(
        string $catnum,
        string $price,
        int $stockQuantity,
        string $partno = null,
        string $ean = null,
        int $weight = null,
        bool $sellingDenied = false,
        int $orderingPriority = 1,
        bool $usingStock = true,

    ): ProductData {
        /** @var \App\Model\Product\ProductData $productData */
        $productData = $this->productDataFactory->create();

        $productData->catnum = $catnum;
        $productData->partno = $partno;
        $productData->ean = $ean;
        $this->setOrderingPriority($productData, $orderingPriority);
        $productData->weight = $weight;

        $parameterTranslations = [];

        foreach ($this->domain->getAllIncludingDomainConfigsWithoutDataCreated() as $domain) {
            $locale = $domain->getLocale();
            $productData->namePrefix[$locale] = t('Television', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->name[$locale] = t('22" Sencor SLE 22F46DM4 HELLO KITTY', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->nameSufix[$locale] = t('plasma', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->descriptions[$domain->getId()] = t('Television LED, 55 cm diagonal, 1920x1080 Full HD, DVB-T MPEG4 tuner with USB recording and playback (DivX, XviD, MP3, WMA, JPEG), HDMI, SCART, VGA, pink execution, energ. Class B', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $domain->getLocale());
            $productData->shortDescriptions[$domain->getId()] = t('Television LED, 55 cm diagonal, 1920x1080 Full HD, DVB-T MPEG4 tuner with USB recording and playback', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $domain->getLocale());

            $productData->seoH1s[$domain->getId()] = t('Hello Kitty Television', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $domain->getLocale());
            $productData->seoTitles[$domain->getId()] = t('Hello Kitty TV', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $domain->getLocale());
            $productData->seoMetaDescriptions[$domain->getId()] = t('Hello Kitty TV, LED, 55 cm diagonal, 1920x1080 Full HD.', [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $domain->getLocale());

        }

        $this->uspsDataProvider->fillUspData($productData);
        $this->vatDataProvider->fillVatData($productData);

        $this->setPriceForAllPricingGroups($productData, $price);

        $this->setSellingFrom($productData, '16.1.2000');
        $this->setSellingTo($productData, null);
        $productData->usingStock = $usingStock;
        $productData->stockQuantity = $stockQuantity;
        $productData->outOfStockAction = Product::OUT_OF_STOCK_ACTION_HIDE;
        $this->setStocksQuantity($productData, $stockQuantity);

        $this->setUnit($productData, UnitDataFixture::UNIT_PIECES);
        $this->setAvailability($productData, AvailabilityDataFixture::AVAILABILITY_IN_STOCK);
        $this->setCategoriesForAllDomains($productData, [CategoryDataFixture::CATEGORY_ELECTRONICS, CategoryDataFixture::CATEGORY_TV, CategoryDataFixture::CATEGORY_PC]);
        $productData->categoriesByDomainId[Domain::SECOND_DOMAIN_ID] = [];
        $productData->categoriesByDomainId[Domain::SECOND_DOMAIN_ID][] = $this->persistentReferenceFacade->getReference(CategoryDataFixture::CATEGORY_ELECTRONICS);
        $productData->categoriesByDomainId[Domain::SECOND_DOMAIN_ID][] = $this->persistentReferenceFacade->getReference(CategoryDataFixture::CATEGORY_TV);
        $productData->categoriesByDomainId[Domain::SECOND_DOMAIN_ID][] = $this->persistentReferenceFacade->getReference(CategoryDataFixture::CATEGORY_BOOKS);

        $this->setFlags($productData, [FlagDataFixture::FLAG_PRODUCT_ACTION]);

        $productData->sellingDenied = false;
        $this->setBrand($productData, BrandDataFixture::BRAND_SENCOR);

        return $productData;
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     * @param string $price
     */
    private function setPriceForAllPricingGroups(ProductData $productData, string $price): void
    {
        foreach ($this->pricingGroupFacade->getAll() as $pricingGroup) {
            /** @var \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat $vat */
            $vat = $this->persistentReferenceFacade->getReferenceForDomain(VatDataFixture::VAT_HIGH, $pricingGroup->getDomainId());
            /** @var \Shopsys\FrameworkBundle\Model\Pricing\Currency\Currency $currencyCzk */
            $currencyCzk = $this->persistentReferenceFacade->getReference(CurrencyDataFixture::CURRENCY_CZK);

            $money = $this->priceConverter->convertPriceToInputPriceWithoutVatInDomainDefaultCurrency(
                Money::create($price),
                $currencyCzk,
                $vat->getPercent(),
                $pricingGroup->getDomainId(),
            );

            $productData->manualInputPricesByPricingGroupId[$pricingGroup->getId()] = $money;
        }
    }

     /**
     * @param \App\Model\Product\ProductData $productData
     * @param int $quantity
     */
    public function setStocksQuantity(ProductData $productData, int $quantity): void
    {
        $stocks = $this->stockRepository->getAllStocks();

        foreach ($stocks as $stock) {
            $productStockData = $this->productStockDataFactory->createFromStock($stock);
            $productStockData->productQuantity = $quantity;
            $productData->productStockData[$stock->getId()] = $productStockData;
        }
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     * @param int $orderingPriority
     */
    private function setOrderingPriority(ProductData $productData, int $orderingPriority): void
    {
        foreach ($this->domain->getAllIds() as $domainId) {
            $productData->orderingPriorityByDomainId[$domainId] = $orderingPriority;
        }
    }
}
