import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const Footer = dynamic(() => import('./Footer').then((component) => component.Footer), { ssr: false });

const FooterPlaceholder = dynamic(() => import('./FooterPlaceholder').then((component) => component.FooterPlaceholder));

export const DeferredFooter = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('footer', hadClientSideNavigation);

    return shouldRender ? <Footer /> : <FooterPlaceholder />;
};
