import { DeferredLoaders } from './DeferredLoaders';
import { Fonts } from './Fonts';
import { DeferredSymfonyDebugToolbar } from 'components/Basic/SymfonyDebugToolbar/DeferredSymfonyDebugToolbar';
import { DeferredUserConsent } from 'components/Blocks/UserConsent/DeferredUserConsent';
import { DeferredGtmHeadScript } from 'gtm/DeferredGtmHeadScript';
import { GtmProvider } from 'gtm/context/GtmProvider';
import { NextComponentType, NextPageContext } from 'next';
import dynamic from 'next/dynamic';
import { ToastContainer } from 'react-toastify';
import { usePersistStoreHydration } from 'utils/app/useStoreHydration';
import { useCookiesStoreSync } from 'utils/cookies/useCookiesStoreSync';
import { ServerSidePropsType } from 'utils/serverSide/initServerSideProps';
import { useSetInitialStoreValues } from 'utils/store/useSetInitialStoreValues';

const Error503Content = dynamic(
    () => import('components/Pages/ErrorPage/Error503Content').then((component) => component.Error503Content),
    {
        ssr: false,
    },
);

type AppPageContentProps = {
    Component: NextComponentType<NextPageContext, any, any>;
    pageProps: ServerSidePropsType;
};

export const AppPageContent: FC<AppPageContentProps> = ({ Component, pageProps }) => {
    usePersistStoreHydration();
    useSetInitialStoreValues(pageProps);
    useCookiesStoreSync();

    if (pageProps.isMaintenance) {
        return <Error503Content />;
    }

    return (
        <GtmProvider>
            <Fonts />
            <DeferredLoaders />
            <DeferredGtmHeadScript />

            <div id="portal" />

            <ToastContainer autoClose={6000} position="top-center" theme="colored" />

            <Component {...pageProps} />

            <DeferredSymfonyDebugToolbar />
            <DeferredUserConsent url={pageProps.domainConfig.url} />
        </GtmProvider>
    );
};
