<?php

declare(strict_types=1);

namespace Shopsys\FrameworkBundle\Model\Product\Brand;

use Shopsys\FrameworkBundle\Component\LocalCache\LocalCacheFacade;
use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade;

class BrandCachedFacade
{
    protected const BRAND_URL_CACHE_NAMESPACE = 'brandUrlCache';

    /**
     * @param \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrlFacade $friendlyUrlFacade
     * @param \Shopsys\FrameworkBundle\Component\LocalCache\LocalCacheFacade $localCacheFacade
     */
    public function __construct(
        protected readonly FriendlyUrlFacade $friendlyUrlFacade,
        protected readonly LocalCacheFacade $localCacheFacade,
    ) {
    }

    /**
     * @param int $brandId
     * @param int $domainId
     * @return string
     */
    public function getBrandUrlByDomainId(int $brandId, int $domainId): string
    {
        return $this->localCacheFacade->getOrSaveValue(
            static::BRAND_URL_CACHE_NAMESPACE,
            fn () => $this->friendlyUrlFacade->getAbsoluteUrlByRouteNameAndEntityId(
                $domainId,
                'front_brand_detail',
                $brandId,
            ),
            $brandId,
            $domainId,
        );
    }
}
