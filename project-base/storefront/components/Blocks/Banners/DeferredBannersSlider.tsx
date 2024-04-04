import { useSliderItemsQuery } from 'graphql/requests/sliderItems/queries/SliderItemsQuery.generated';
import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const BannersSlider = dynamic(() => import('./BannersSlider').then((component) => component.BannersSlider), {
    ssr: false,
    loading: () => <BannersSliderPlaceholder />,
});

const BannersSliderPlaceholder = dynamic(() =>
    import('./BannersSliderPlaceholder').then((component) => component.BannersSliderPlaceholder),
);

const SkeletonModuleBanners = dynamic(
    () =>
        import('components/Blocks/Skeleton/SkeletonModuleBanners').then((component) => component.SkeletonModuleBanners),
    {
        ssr: false,
    },
);

export const DeferredBannersSlider: FC = () => {
    const [{ data: sliderItemsData, fetching }] = useSliderItemsQuery();
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('banners', hadClientSideNavigation);

    if (fetching) {
        return <SkeletonModuleBanners />;
    }

    if (!sliderItemsData?.sliderItems.length) {
        return null;
    }

    return shouldRender ? <BannersSlider sliderItems={sliderItemsData.sliderItems} /> : <BannersSliderPlaceholder />;
};
