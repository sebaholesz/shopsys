import { MetaRobots } from 'components/Basic/Head/MetaRobots';
import { SkeletonPageConfirmation } from 'components/Blocks/Skeleton/SkeletonPageConfirmation';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { Webline } from 'components/Layout/Webline/Webline';
import { PaymentFail } from 'components/Pages/Order/PaymentConfirmation/PaymentFail';
import { PaymentSuccess } from 'components/Pages/Order/PaymentConfirmation/PaymentSuccess';
import { useUpdatePaymentStatus } from 'components/Pages/Order/PaymentConfirmation/utils';
import useTranslation from 'next-translate/useTranslation';
import { useRouter } from 'next/router';
import { getStringFromUrlQuery } from 'utils/parsing/getStringFromUrlQuery';
import { getServerSidePropsWrapper } from 'utils/serverSide/getServerSidePropsWrapper';
import { initServerSideProps, ServerSidePropsType } from 'utils/serverSide/initServerSideProps';

const OrderPaymentConfirmationPage: FC<ServerSidePropsType> = () => {
    const { t } = useTranslation();

    const { orderIdentifier, orderPaymentStatusPageValidityHash } = useRouter().query;
    const orderUuid = getStringFromUrlQuery(orderIdentifier);
    const orderPaymentStatusPageValidityHashParam = getStringFromUrlQuery(orderPaymentStatusPageValidityHash);
    const paymentStatusData = useUpdatePaymentStatus(orderUuid, orderPaymentStatusPageValidityHashParam);

    return (
        <>
            <MetaRobots content="noindex" />
            <CommonLayout title={t('Order sent')}>
                <Webline>
                    {!paymentStatusData ? (
                        <SkeletonPageConfirmation />
                    ) : (
                        <>
                            {paymentStatusData.UpdatePaymentStatus.isPaid ? (
                                <PaymentSuccess orderUuid={orderUuid} />
                            ) : (
                                <PaymentFail
                                    lastUsedOrderPaymentType={paymentStatusData.UpdatePaymentStatus.payment.type}
                                    orderUuid={orderUuid}
                                    paymentTransactionCount={
                                        paymentStatusData.UpdatePaymentStatus.paymentTransactionsCount
                                    }
                                />
                            )}
                        </>
                    )}
                </Webline>
            </CommonLayout>
        </>
    );
};

export const getServerSideProps = getServerSidePropsWrapper(({ redisClient, domainConfig, t }) => async (context) => {
    const orderUuid = getStringFromUrlQuery(context.query.orderIdentifier);

    if (orderUuid === '') {
        return {
            redirect: {
                destination: '/',
                statusCode: 301,
            },
        };
    }

    return initServerSideProps({
        context,
        redisClient,
        domainConfig,
        t,
    });
});

export default OrderPaymentConfirmationPage;
