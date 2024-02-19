import { useCurrentCart } from './useCurrentCart';
import { getInternationalizedStaticUrls } from 'helpers/getInternationalizedStaticUrls';
import { useDomainConfig } from 'hooks/useDomainConfig';
import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';

export const useOrderPagesAccess = (page: 'transport-and-payment' | 'contact-information') => {
    const router = useRouter();
    const { cart, isFetching } = useCurrentCart();
    const { url } = useDomainConfig();
    const [canContentBeDisplayed, setCanContentBeDisplayed] = useState(false);
    const [cartUrl, transportAndPaymentUrl] = getInternationalizedStaticUrls(
        ['/cart', '/order/transport-and-payment'],
        url,
    );

    const handleOrderPagesAccess = () => {
        if (cart === undefined || isFetching) {
            return;
        }

        if (cart === null || !cart.items.length) {
            router.replace(cartUrl);
        } else if (page === 'contact-information' && (!cart.transport || !cart.payment)) {
            router.replace(transportAndPaymentUrl);
        } else {
            setCanContentBeDisplayed(true);
        }
    };

    useEffect(() => {
        handleOrderPagesAccess();
    }, [cart, isFetching]);

    return canContentBeDisplayed;
};
