import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const MenuIconicPlaceholder = dynamic(() =>
    import('./MenuIconicPlaceholder').then((component) => component.MenuIconicPlaceholder),
);

const MenuIconic = dynamic(() => import('./MenuIconic').then((component) => component.MenuIconic), {
    ssr: false,
    loading: () => <MenuIconicPlaceholder />,
});

export const DeferredMenuIconic: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('menu_iconic', hadClientSideNavigation);

    return <div className="order-2 flex">{shouldRender ? <MenuIconic /> : <MenuIconicPlaceholder />}</div>;
};
