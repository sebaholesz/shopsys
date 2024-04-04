import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const NewsletterForm = dynamic(() => import('./NewsletterForm').then((component) => component.NewsletterForm), {
    ssr: false,
});

export const DeferredNewsletterForm: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('newsletter', hadClientSideNavigation);

    return shouldRender ? <NewsletterForm /> : null;
};
