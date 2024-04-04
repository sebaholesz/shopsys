import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const LastVisitedProducts = dynamic(
    () => import('./LastVisitedProducts').then((component) => component.LastVisitedProducts),
    {
        ssr: false,
    },
);

export const DeferredLastVisitedProducts: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('last_visited', hadClientSideNavigation);

    return shouldRender ? <LastVisitedProducts /> : null;
};
