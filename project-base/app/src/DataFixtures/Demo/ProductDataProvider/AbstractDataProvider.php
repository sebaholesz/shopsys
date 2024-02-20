<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo\ProductDataProvider;

use Generator;

abstract class AbstractDataProvider
{
    /**
     * @return \Generator
     */
    abstract protected function provideData(): Generator;


    protected function getData(string $catnum): mixed
    {
        $data = iterator_to_array($this->provideData());

        if (!array_key_exists($catnum, $data)) {
            throw new DataNotFoundException('No data for catnum ' . $catnum);
        }

        return $data[$catnum];
    }

}
