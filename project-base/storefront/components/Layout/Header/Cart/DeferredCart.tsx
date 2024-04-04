import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const CartPlaceholder = dynamic(() => import('./CartPlaceholder').then((component) => component.CartPlaceholder));

const Cart = dynamic(() => import('./Cart').then((component) => component.Cart), {
    ssr: false,
    loading: () => <CartPlaceholder />,
});

export const DeferredCart: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('cart_in_header', hadClientSideNavigation);

    return shouldRender ? <Cart className="order-3 vl:order-4" /> : <CartPlaceholder />;
};
