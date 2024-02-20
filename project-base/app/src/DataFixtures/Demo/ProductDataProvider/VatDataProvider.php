<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo\ProductDataProvider;

use App\DataFixtures\Demo\VatDataFixture;
use App\Model\Product\ProductData;
use Exception;
use Generator;
use Shopsys\FrameworkBundle\Component\DataFixture\PersistentReferenceFacade;
use Shopsys\FrameworkBundle\Component\Domain\Domain;

class VatDataProvider extends AbstractDataProvider
{
    public function __construct(
        private readonly Domain $domain,
        private readonly PersistentReferenceFacade $persistentReferenceFacade,
    ) {
    }

    /**
     * @return \Generator
     */
    protected function provideData(): Generator
    {
        yield '9176544M3' => VatDataFixture::VAT_ZERO;
        yield '9176544MF' => VatDataFixture::VAT_LOW;
        yield '5965907' => VatDataFixture::VAT_LOW;
        yield '5964034' => VatDataFixture::VAT_SECOND_LOW;
        // all others are high
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     */
    public function fillVatData(ProductData $productData): void
    {
        try {
            $data = $this->getData($productData->catnum);
            $vat = $data;
        } catch (Exception) {
            $vat = VatDataFixture::VAT_HIGH;
        }

        $this->setVat($productData, $vat);
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     * @param string $vatReference
     */
    private function setVat(ProductData $productData, string $vatReference): void
    {
        /** @var \Shopsys\FrameworkBundle\Model\Pricing\Vat\Vat[] $productVatsIndexedByDomainId */
        $productVatsIndexedByDomainId = [];

        foreach ($this->domain->getAllIds() as $domainId) {
            $productVatsIndexedByDomainId[$domainId] = $this->persistentReferenceFacade->getReferenceForDomain($vatReference, Domain::FIRST_DOMAIN_ID);
        }

        $productData->vatsIndexedByDomainId = $productVatsIndexedByDomainId;
    }
}
