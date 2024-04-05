import { DeferredFooter } from './Footer/DeferredFooter';
import { DeferredNewsletterForm } from './Footer/DeferredNewsletterForm';
import { Header } from './Header/Header';
import { DeferredNavigation } from './Header/Navigation/DeferredNavigation';
import { NotificationBars } from './NotificationBars/NotificationBars';
import { Webline } from './Webline/Webline';
import { SeoMeta } from 'components/Basic/Head/SeoMeta';
import { Adverts } from 'components/Blocks/Adverts/Adverts';
import { SkeletonManager } from 'components/Blocks/Skeleton/SkeletonManager';
import { TypeBreadcrumbFragment } from 'graphql/requests/breadcrumbs/fragments/BreadcrumbFragment.generated';
import { TypeHreflangLink } from 'graphql/types';
import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { FriendlyPagesTypesKey } from 'types/friendlyUrl';
import { CanonicalQueryParameters } from 'utils/seo/generateCanonicalUrl';

const Breadcrumbs = dynamic(() => import('./Breadcrumbs/Breadcrumbs').then((component) => component.Breadcrumbs));

type CommonLayoutProps = {
    title?: string | null;
    description?: string | null;
    breadcrumbs?: TypeBreadcrumbFragment[];
    breadcrumbsType?: FriendlyPagesTypesKey;
    canonicalQueryParams?: CanonicalQueryParameters;
    hreflangLinks?: TypeHreflangLink[];
    isFetchingData?: boolean;
};

export const CommonLayout: FC<CommonLayoutProps> = ({
    children,
    description,
    title,
    breadcrumbs,
    breadcrumbsType,
    canonicalQueryParams,
    hreflangLinks,
    isFetchingData,
}) => {
    const isPageLoading = useSessionStore((s) => s.isPageLoading);

    return (
        <>
            <SeoMeta
                canonicalQueryParams={canonicalQueryParams}
                defaultDescription={description}
                defaultHreflangLinks={hreflangLinks}
                defaultTitle={title}
            />

            <NotificationBars />

            <Webline className="relative mb-8" type="colored">
                <Header />
                <DeferredNavigation />
            </Webline>

            <Adverts withGapBottom withWebline positionName="header" />

            {!!breadcrumbs && !isPageLoading && !isFetchingData && (
                <Webline className="mb-8">
                    <Breadcrumbs breadcrumbs={breadcrumbs} type={breadcrumbsType} />
                </Webline>
            )}

            <SkeletonManager isFetchingData={isFetchingData} isPageLoading={isPageLoading}>
                {children}
            </SkeletonManager>

            <Adverts withGapBottom withGapTop withWebline positionName="footer" />

            <Webline type="light">
                <DeferredNewsletterForm />
            </Webline>

            <Webline type="dark">
                <DeferredFooter />
            </Webline>
        </>
    );
};
