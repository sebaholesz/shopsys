import dynamic from 'next/dynamic';
import { useEffect } from 'react';
import { useSessionStore } from 'store/useSessionStore';

const SkeletonPageArticle = dynamic(
    () => import('./SkeletonPageArticle').then((component) => component.SkeletonPageArticle),
    { ssr: false },
);
const SkeletonPageBlogCategory = dynamic(
    () => import('./SkeletonPageBlogCategory').then((component) => component.SkeletonPageBlogCategory),
    { ssr: false },
);
const SkeletonPageComparison = dynamic(
    () => import('./SkeletonPageComparison').then((component) => component.SkeletonPageComparison),
    { ssr: false },
);
const SkeletonPageHome = dynamic(() => import('./SkeletonPageHome').then((component) => component.SkeletonPageHome), {
    ssr: false,
});
const SkeletonPageOrder = dynamic(
    () => import('./SkeletonPageOrder').then((component) => component.SkeletonPageOrder),
    { ssr: false },
);
const SkeletonPageOrders = dynamic(
    () => import('./SkeletonPageOrders').then((component) => component.SkeletonPageOrders),
    { ssr: false },
);
const SkeletonPageProductDetail = dynamic(
    () => import('./SkeletonPageProductDetail').then((component) => component.SkeletonPageProductDetail),
    { ssr: false },
);
const SkeletonPageProductDetailMainVariant = dynamic(
    () =>
        import('./SkeletonPageProductDetailMainVariant').then(
            (component) => component.SkeletonPageProductDetailMainVariant,
        ),
    { ssr: false },
);
const SkeletonPageProductsList = dynamic(
    () => import('./SkeletonPageProductsList').then((component) => component.SkeletonPageProductsList),
    { ssr: false },
);
const SkeletonPageProductsListSimple = dynamic(
    () => import('./SkeletonPageProductsListSimple').then((component) => component.SkeletonPageProductsListSimple),
    { ssr: false },
);
const SkeletonPageStore = dynamic(
    () => import('./SkeletonPageStore').then((component) => component.SkeletonPageStore),
    { ssr: false },
);
const SkeletonPageStores = dynamic(
    () => import('./SkeletonPageStores').then((component) => component.SkeletonPageStores),
    { ssr: false },
);
const SkeletonPageWishlist = dynamic(
    () => import('./SkeletonPageWishlist').then((component) => component.SkeletonPageWishlist),
    { ssr: false },
);

type SkeletonManagerProps = {
    isFetchingData?: boolean;
    isPageLoading: boolean;
};

export const SkeletonManager: FC<SkeletonManagerProps> = ({ isFetchingData, isPageLoading, children }) => {
    const redirectPageType = useSessionStore((s) => s.redirectPageType);

    useEffect(() => {
        if (isPageLoading) {
            window.scrollTo({ top: 0 });
        }
    }, [isPageLoading]);

    if (!isPageLoading && !isFetchingData) {
        return <>{children}</>;
    }

    switch (redirectPageType) {
        case 'homepage':
            return <SkeletonPageHome />;
        case 'product':
            return <SkeletonPageProductDetail />;
        case 'productMainVariant':
            return <SkeletonPageProductDetailMainVariant />;
        case 'category':
        case 'seo_category':
            return <SkeletonPageProductsList />;
        case 'brand':
        case 'flag':
            return <SkeletonPageProductsListSimple />;
        case 'article':
        case 'blogArticle':
            return <SkeletonPageArticle />;
        case 'blogCategory':
            return <SkeletonPageBlogCategory />;
        case 'stores':
            return <SkeletonPageStores />;
        case 'store':
            return <SkeletonPageStore />;
        case 'wishlist':
            return <SkeletonPageWishlist />;
        case 'comparison':
            return <SkeletonPageComparison />;
        case 'orders':
            return <SkeletonPageOrders />;
        case 'order':
            return <SkeletonPageOrder />;
        default:
            return null;
    }
};
