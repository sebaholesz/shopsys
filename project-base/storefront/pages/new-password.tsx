import { MetaRobots } from 'components/Basic/Head/MetaRobots';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { NewPasswordContent } from 'components/Pages/NewPassword/NewPasswordContent';
import { useGtmStaticPageViewEvent } from 'gtm/helpers/eventFactories';
import { getInternationalizedStaticUrls } from 'helpers/getInternationalizedStaticUrls';
import { getServerSidePropsWrapper } from 'helpers/serverSide/getServerSidePropsWrapper';
import { initServerSideProps, ServerSidePropsType } from 'helpers/serverSide/initServerSideProps';
import { useGtmPageViewEvent } from 'gtm/hooks/useGtmPageViewEvent';
import { useTypedTranslationFunction } from 'hooks/typescript/useTypedTranslationFunction';
import { useDomainConfig } from 'hooks/useDomainConfig';
import { useRouter } from 'next/router';
import { GtmPageType } from 'gtm/types/enums';
import { BreadcrumbFragmentApi } from 'graphql/requests/breadcrumbs/fragments/BreadcrumbFragment.generated';

const NewPasswordPage: FC<ServerSidePropsType> = () => {
    const t = useTypedTranslationFunction();
    const { url } = useDomainConfig();
    const [newPasswordUrl] = getInternationalizedStaticUrls(['/new-password'], url);
    const breadcrumbs: BreadcrumbFragmentApi[] = [
        { __typename: 'Link', name: t('Set new password'), slug: newPasswordUrl },
    ];
    const gtmStaticPageViewEvent = useGtmStaticPageViewEvent(GtmPageType.other, breadcrumbs);
    useGtmPageViewEvent(gtmStaticPageViewEvent);

    const router = useRouter();
    const { hash, email } = router.query;

    let hashParam = '';
    if (hash !== undefined) {
        if (Array.isArray(hash)) {
            hashParam = hash[0];
        } else if (hash.trim() !== '') {
            hashParam = hash.trim();
        }
    }

    let emailParam = '';
    if (email !== undefined) {
        if (Array.isArray(email)) {
            emailParam = email[0];
        } else if (email.trim() !== '') {
            emailParam = email.trim();
        }
    }

    return (
        <>
            <MetaRobots content="noindex" />
            <CommonLayout title={t('Set new password')}>
                <NewPasswordContent hash={hashParam} email={emailParam} breadcrumbs={breadcrumbs} />
            </CommonLayout>
        </>
    );
};

export const getServerSideProps = getServerSidePropsWrapper(
    ({ redisClient, domainConfig, t }) =>
        async (context) =>
            initServerSideProps({ context, redisClient, domainConfig, t }),
);

export default NewPasswordPage;
