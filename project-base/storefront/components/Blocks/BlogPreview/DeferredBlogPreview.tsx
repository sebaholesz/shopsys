import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const BlogPreview = dynamic(() => import('./BlogPreview').then((component) => component.BlogPreview), { ssr: false });

const BlogPreviewPlaceholder = dynamic(() =>
    import('./BlogPreviewPlaceholder').then((component) => component.BlogPreviewPlaceholder),
);

export const DeferredBlogPreview: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('blog_preview', hadClientSideNavigation);

    return shouldRender ? <BlogPreview /> : <BlogPreviewPlaceholder />;
};
