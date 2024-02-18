<?php

declare(strict_types=1);

namespace Tests\FrameworkBundle\Unit\Component\LocalCache;

use PHPUnit\Framework\TestCase;
use Shopsys\FrameworkBundle\Component\LocalCache\LocalCacheFacade;

class LocalCacheFacadeTest extends TestCase
{
    public function testGetAndSaveValue(): void
    {
        $getValueCallback = fn () => 4 * 5;
        $namespace = 'test';
        $expectedValue = $getValueCallback();

        $localCacheFacade = new LocalCacheFacade();

        $value = $localCacheFacade->getOrSaveValue($namespace, $getValueCallback, 123, 'zxc@seznam.cz', true, 78.9);
        $this->assertSame($expectedValue, $value);
    }

    public function testGetKeyFromParts(): void
    {
        $expectedKey = '123~zxc~seznam~cz~1~78~9';
        $localCacheFacade = new LocalCacheFacade();
        $key = $localCacheFacade->getKeyFromParts(123, 'zxc@seznam.cz', true, 78.9);

        $this->assertSame($expectedKey, $key);
    }

    public function testLocalCacheFacadeSave(): void
    {
        $namespace = 'test';
        $key = 'first';
        $expectedValue = 'value';

        $localCacheFacade = new LocalCacheFacade();
        $localCacheFacade->save($namespace, $key, $expectedValue);

        $value = $localCacheFacade->getItem($namespace, $key);
        $this->assertSame($expectedValue, $value);
    }

    public function testLocalCacheFacadeDelete(): void
    {
        $namespace = 'test';
        $key = 'first';
        $expectedValue = 'value';

        $localCacheFacade = new LocalCacheFacade();
        $localCacheFacade->save($namespace, $key, $expectedValue);

        $value = $localCacheFacade->getItem($namespace, $key);
        $this->assertSame($expectedValue, $value);

        $this->assertTrue($localCacheFacade->hasItem($namespace, $key));
        $localCacheFacade->deleteItem($namespace, $key);
        $this->assertFalse($localCacheFacade->hasItem($namespace, $key));
    }

    public function testLocalCacheFacadeEditValue(): void
    {
        $namespace = 'test';
        $key = 'first';
        $firstValue = 'value';
        $expectedValue = 'new value';

        $localCacheFacade = new LocalCacheFacade();
        $localCacheFacade->save($namespace, $key, $firstValue);

        $value = $localCacheFacade->getItem($namespace, $key);
        $this->assertSame($firstValue, $value);

        $localCacheFacade->save($namespace, $key, $expectedValue);
        $value = $localCacheFacade->getItem($namespace, $key);
        $this->assertSame($expectedValue, $value);
    }

    public function testLocalCacheFacadeValuesByNamespace(): void
    {
        $namespace = 'test';
        $key = 'first';
        $value = 'value';

        $expectedArray = [
            $key => $value,
        ];

        $localCacheFacade = new LocalCacheFacade();
        $localCacheFacade->save($namespace, $key, $value);

        $values = $localCacheFacade->getValuesByNamespace($namespace);
        $this->assertSame($expectedArray, $values);
    }

    public function testLocalCacheFacadeReset(): void
    {
        $namespace = 'test';
        $key = 'first';
        $value = 'value';

        $expectedArray = [
            $key => $value,
        ];

        $localCacheFacade = new LocalCacheFacade();
        $localCacheFacade->save($namespace, $key, $value);

        $values = $localCacheFacade->getValuesByNamespace($namespace);
        $this->assertSame($expectedArray, $values);

        $localCacheFacade->reset();

        $this->assertFalse($localCacheFacade->hasItem($namespace, $key));
    }
}
