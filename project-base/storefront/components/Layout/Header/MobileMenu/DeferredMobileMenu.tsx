import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { desktopFirstSizes } from 'utils/mediaQueries';
import { useGetWindowSize } from 'utils/ui/useGetWindowSize';
import { useDeferredRender } from 'utils/useDeferredRender';

const MobileMenuPlaceholder = dynamic(() =>
    import('./MobileMenuPlaceholder').then((component) => component.MobileMenuPlaceholder),
);

const MobileMenu = dynamic(() => import('./MobileMenu').then((component) => component.MobileMenu), {
    ssr: false,
    loading: () => <MobileMenuPlaceholder />,
});

export const DeferredMobileMenu: FC = () => {
    const { width: windowWidth } = useGetWindowSize();
    const isDesktop = windowWidth > desktopFirstSizes.tablet;
    const isRecognizingWindowWidth = windowWidth < 0;
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('mobile_menu', hadClientSideNavigation);

    return (
        <div className="order-4 ml-3 flex cursor-pointer items-center justify-center text-lg lg:hidden">
            {shouldRender && isRecognizingWindowWidth && !isDesktop ? <MobileMenu /> : <MobileMenuPlaceholder />}
        </div>
    );
};
