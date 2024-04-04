import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const Loaders = dynamic(() => import('components/Pages/App/Loaders').then((component) => component.Loaders), {
    ssr: false,
});

export const DeferredLoaders = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('loaders', hadClientSideNavigation);

    return shouldRender ? <Loaders /> : null;
};
