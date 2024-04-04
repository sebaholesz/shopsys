import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const Navigation = dynamic(() => import('./Navigation').then((component) => component.Navigation), {
    ssr: false,
    loading: () => <NavigationPlaceholder />,
});

const NavigationPlaceholder = dynamic(() =>
    import('./NavigationPlaceholder').then((component) => component.NavigationPlaceholder),
);

export const DeferredNavigation: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('navigation', hadClientSideNavigation);

    return shouldRender ? <Navigation /> : <NavigationPlaceholder />;
};
