import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const GtmEvents = dynamic(() => import('./GtmEvents').then((component) => component.GtmEvents), {
    ssr: false,
});

export const DeferredGtmEvents: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('gtm_events', hadClientSideNavigation);

    return shouldRender ? <GtmEvents /> : null;
};
