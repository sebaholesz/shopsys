<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Component\LocalCache;

use Shopsys\FrameworkBundle\Component\LocalCache\Exception\NamespaceCacheKeyNotFoundException;
use Shopsys\FrameworkBundle\Component\LocalCache\Exception\ValueCacheKeyNotFoundException;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Contracts\Service\ResetInterface;

class LocalCacheFacade implements ResetInterface
{
    protected const NOT_ALLOWED_CHARS = '{}()/\@:".';

    protected ArrayAdapter $namespacesCache;

    public function __construct()
    {
        $this->namespacesCache = new ArrayAdapter(0, false);
    }

    /**
     * @param string $namespace
     * @param string $key
     * @return mixed
     */
    public function getItem(string $namespace, string $key): mixed
    {
        if (!$this->hasNamespaceCache($namespace)) {
            throw new NamespaceCacheKeyNotFoundException($namespace);
        }

        $this->fixKey($key);

        if (!$this->hasItem($namespace, $key)) {
            throw new ValueCacheKeyNotFoundException($namespace, $key);
        }

        return $this->getNamespaceCache($namespace)->getItem($key)->get();
    }

    /**
     * @param string $namespace
     * @return array
     */
    public function getValuesByNamespace(string $namespace): array
    {
        if (!$this->hasNamespaceCache($namespace)) {
            throw new NamespaceCacheKeyNotFoundException($namespace);
        }

        return $this->getNamespaceCache($namespace)->getValues();
    }

    /**
     * @param string $namespace
     * @return \Symfony\Component\Cache\Adapter\ArrayAdapter
     */
    protected function getNamespaceCache(string $namespace): ArrayAdapter
    {
        return $this->namespacesCache->getItem($namespace)->get();
    }

    /**
     * @param string $namespace
     * @param string $key
     * @return bool
     */
    public function hasItem(string $namespace, string $key): bool
    {
        if ($this->hasNamespaceCache($namespace)) {
            $this->fixKey($key);

            return $this->getNamespaceCache($namespace)->hasItem($key);
        }

        return false;
    }

    /**
     * @param string $namespace
     * @return bool
     */
    protected function hasNamespaceCache(string $namespace): bool
    {
        return $this->namespacesCache->hasItem($namespace);
    }

    public function reset(): void
    {
        $this->namespacesCache->reset();
    }

    /**
     * @param string $namespace
     * @param string $key
     */
    public function deleteItem(string $namespace, string $key): void
    {
        if (!$this->hasNamespaceCache($namespace)) {
            return;
        }

        $this->fixKey($key);
        $namespaceCache = $this->getNamespaceCache($namespace);
        $namespaceCache->deleteItem($key);
    }

    /**
     * @param string $namespace
     * @param string $key
     * @param mixed $value
     */
    public function save(string $namespace, string $key, mixed $value): void
    {
        $this->fixKey($key);
        $namespaceCacheItem = $this->namespacesCache->getItem($namespace);

        if (!$this->hasNamespaceCache($namespace)) {
            $namespaceCacheItem->set(new ArrayAdapter(0, false));
            $this->namespacesCache->save($namespaceCacheItem);
        }

        /** @var \Symfony\Component\Cache\Adapter\ArrayAdapter $namespaceCache */
        $namespaceCache = $namespaceCacheItem->get();
        $valueItem = $namespaceCache->getItem($key);
        $valueItem->set($value);
        $namespaceCache->save($valueItem);
    }

    /**
     * @param string $key
     */
    protected function fixKey(string &$key): void
    {
        foreach (str_split(static::NOT_ALLOWED_CHARS) as $char) {
            $key = str_replace($char, '~', $key);
        }
    }

    /**
     * @param string $namespace
     * @param callable $getValueCallback
     * @param string|int|float|bool ...$keyParts List of variables for building cache key
     * @return mixed
     */
    public function getOrSaveValue(string $namespace, callable $getValueCallback, ...$keyParts): mixed
    {
        $key = $this->buildKey($keyParts);

        if ($this->hasItem($namespace, $key)) {
            return $this->getItem($namespace, $key);
        }
        $value = $getValueCallback();
        $this->save($namespace, $key, $value);

        return $value;
    }

    /**
     * @param array $keyParts
     * @return string
     */
    protected function buildKey(array $keyParts): string
    {
        $key = implode('~', $keyParts);
        $this->fixKey($key);

        return $key;
    }

    /**
     * @param string|int|float|bool ...$keyParts List of variables for building cache key
     * @return string
     */
    public function getKeyFromParts(...$keyParts): string
    {
        return $this->buildKey($keyParts);
    }
}
