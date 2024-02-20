<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo\ProductDataProvider;

use App\Model\Product\ProductData;
use DateTime;
use Exception;
use Generator;

class SellingToProvider extends AbstractDataProvider
{
    /**
     * @return \Generator
     */
    protected function provideData(): Generator
    {
        yield '5964035' => '25.1.2015';
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     */
    public function fillSellingTo(ProductData $productData): void
    {
        try {
            $data = $this->getData($productData->catnum);
            $productData->sellingTo = new DateTime($data);
        } catch (Exception) {
            return;
        }
    }

    /**
     * @param \App\Model\Product\ProductData $productData
     * @param string $date
     */
    private function setSellingTo(ProductData $productData, string $date): void
    {
        $productData->sellingTo = new DateTime($date);
    }
}
