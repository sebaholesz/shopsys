import { useGtmContext } from 'gtm/context/useGtmContext';
import { getGtmContactInformationPageViewEvent } from 'gtm/factories/getGtmContactInformationPageViewEvent';
import { GtmPageViewEventType } from 'gtm/types/events';
import { gtmSafePushEvent } from 'gtm/utils/gtmSafePushEvent';
import { useEffect, useRef } from 'react';

export const useGtmContactInformationPageViewEvent = (gtmPageViewEvent: GtmPageViewEventType): void => {
    const wasViewedRef = useRef(false);
    const { didPageViewRun } = useGtmContext();

    useEffect(() => {
        if (didPageViewRun && gtmPageViewEvent._isLoaded && gtmPageViewEvent.cart && !wasViewedRef.current) {
            wasViewedRef.current = true;
            gtmSafePushEvent(getGtmContactInformationPageViewEvent(gtmPageViewEvent.cart));
        }
    }, [gtmPageViewEvent._isLoaded, gtmPageViewEvent.cart, didPageViewRun]);
};
