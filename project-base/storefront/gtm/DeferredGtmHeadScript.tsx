import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const GtmHeadScript = dynamic(() => import('gtm/GtmHeadScript').then((component) => component.GtmHeadScript), {
    ssr: false,
});

export const DeferredGtmHeadScript: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('gtm_head_script', hadClientSideNavigation);

    return shouldRender ? <GtmHeadScript /> : null;
};
