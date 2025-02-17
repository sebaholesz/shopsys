import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { DEFAULT_PAGE_SIZE } from 'config/constants';
import { TypeListedProductFragment } from 'graphql/requests/products/fragments/ListedProductFragment.generated';
import { useGtmContext } from 'gtm/context/useGtmContext';
import { GtmProductListNameType } from 'gtm/enums/GtmProductListNameType';
import { getGtmProductListViewEvent } from 'gtm/factories/getGtmProductListViewEvent';
import { gtmSafePushEvent } from 'gtm/utils/gtmSafePushEvent';
import { useEffect, useRef } from 'react';
import { useCurrentLoadMoreQuery } from 'utils/queryParams/useCurrentLoadMoreQuery';
import { useCurrentPageQuery } from 'utils/queryParams/useCurrentPageQuery';

export const useGtmPaginatedProductListViewEvent = (
    paginatedProducts: TypeListedProductFragment[] | undefined,
    gtmProductListName: GtmProductListNameType,
): void => {
    const lastViewedStringifiedProducts = useRef<string>();
    const currentPage = useCurrentPageQuery();
    const currentLoadMore = useCurrentLoadMoreQuery();
    const previousLoadMoreRef = useRef(currentLoadMore);
    const { url } = useDomainConfig();
    const stringifiedProducts = JSON.stringify(paginatedProducts);
    const { didPageViewRun } = useGtmContext();

    useEffect(() => {
        if (didPageViewRun && paginatedProducts && lastViewedStringifiedProducts.current !== stringifiedProducts) {
            lastViewedStringifiedProducts.current = stringifiedProducts;

            let paginatedProductsSlice = paginatedProducts;
            if (previousLoadMoreRef.current !== currentLoadMore) {
                paginatedProductsSlice = paginatedProductsSlice.slice(currentLoadMore * DEFAULT_PAGE_SIZE);
                previousLoadMoreRef.current = currentLoadMore;
            }

            gtmSafePushEvent(
                getGtmProductListViewEvent(
                    paginatedProductsSlice,
                    gtmProductListName,
                    currentPage + currentLoadMore,
                    DEFAULT_PAGE_SIZE,
                    url,
                ),
            );
        }
    }, [gtmProductListName, currentPage, url, currentLoadMore, stringifiedProducts, didPageViewRun]);
};
