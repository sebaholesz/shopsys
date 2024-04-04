import { TIDs } from 'cypress/tids';
import { usePromotedProductsQuery } from 'graphql/requests/products/queries/PromotedProductsQuery.generated';
import { GtmProductListNameType } from 'gtm/enums/GtmProductListNameType';
import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const SkeletonModulePromotedProducts = dynamic(
    () =>
        import('components/Blocks/Skeleton/SkeletonModulePromotedProducts').then(
            (component) => component.SkeletonModulePromotedProducts,
        ),
    { ssr: false },
);

const ProductsSlider = dynamic(() => import('./ProductsSlider').then((component) => component.ProductsSlider), {
    ssr: false,
    loading: () => <PromotedProductsProductsSliderPlaceholder />,
});

const PromotedProductsProductsSliderPlaceholder = dynamic(() =>
    import('./PromotedProductsProductsSliderPlaceholder').then(
        (component) => component.PromotedProductsProductsSliderPlaceholder,
    ),
);

export const DeferredPromotedProducts: FC = () => {
    const [{ data: promotedProductsData, fetching }] = usePromotedProductsQuery();
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('promoted_products', hadClientSideNavigation);

    if (fetching) {
        return <SkeletonModulePromotedProducts />;
    }

    if (!promotedProductsData?.promotedProducts) {
        return null;
    }

    return shouldRender ? (
        <ProductsSlider
            gtmProductListName={GtmProductListNameType.homepage_promo_products}
            products={promotedProductsData.promotedProducts}
            tid={TIDs.blocks_product_slider_promoted_products}
        />
    ) : (
        <PromotedProductsProductsSliderPlaceholder />
    );
};
