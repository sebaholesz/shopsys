<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo\ProductDataProvider;

use App\Model\Product\ProductData;
use Exception;
use Generator;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Translation\Translator;

class UspsDataProvider extends AbstractDataProvider
{
    public function __construct(
        private readonly Domain $domain,
    ) {
    }

    /**
     * @return \Generator
     */
    protected function provideData(): Generator
    {
        yield '5960453' => [
            'Seamless Control',
            'Unleash Your Gaming Potential',
            '2000 DPI',
            'Ergonomic Excellence',
            'Responsive and Reliable',
        ];

        yield '9177759' => [
            'Hello Kitty approved',
            'Immersive Full HD resolution',
            'Energy-Efficient Design',
            'Wide Color Gamut',
            'Adaptive Sync Technology',
        ];
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     */
    public function fillUspData(ProductData $productData): void
    {
        try {
            $data = $this->getData($productData->catnum);
        } catch (Exception) {
            return;
        }

        foreach ($this->domain->getAllIncludingDomainConfigsWithoutDataCreated() as $domain) {
            $locale = $domain->getLocale();
            $domainId = $domain->getId();

            $productData->shortDescriptionUsp1ByDomainId[$domainId] = t($data[0], [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->shortDescriptionUsp2ByDomainId[$domainId] = t($data[1], [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->shortDescriptionUsp3ByDomainId[$domainId] = t($data[2], [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->shortDescriptionUsp4ByDomainId[$domainId] = t($data[3], [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
            $productData->shortDescriptionUsp5ByDomainId[$domainId] = t($data[4], [], Translator::DATA_FIXTURES_TRANSLATION_DOMAIN, $locale);
        }
    }
}
