import { getUrlWithoutGetParameters } from './parsing/getUrlWithoutGetParameters';
import { useRouter } from 'next/router';
import { useEffect, useState } from 'react';

export const useDeferredRender = (place: HomePageDeferPlace, shouldRenderInitially: boolean) => {
    const asPath = useRouter().asPath;
    const isDeferredPage = getUrlWithoutGetParameters(asPath) === '/';
    const [shouldRender, setShouldRender] = useState(!isDeferredPage || shouldRenderInitially);

    useEffect(() => {
        const defer = getPageDefer(place);
        const timer = setTimeout(() => {
            setShouldRender(true);
        }, defer);

        return () => {
            clearTimeout(timer);
        };
    }, []);

    return shouldRender;
};

type HomePageDeferPlace =
    | 'loaders'
    | 'blog_preview'
    | 'footer'
    | 'banners'
    | 'promoted_products'
    | 'user_consent'
    | 'autocomplete_search'
    | 'cart_in_header'
    | 'menu_iconic'
    | 'navigation'
    | 'mobile_menu'
    | 'newsletter'
    | 'last_visited'
    | 'gtm_head_script'
    | 'gtm_events';

const getPageDefer = (place: HomePageDeferPlace) => {
    switch (place) {
        case 'loaders':
            return computeDefer(0);
        case 'blog_preview':
            return computeDefer(2);
        case 'footer':
            return computeDefer(3);
        case 'banners':
            return computeDefer(4);
        case 'promoted_products':
            return computeDefer(5);
        case 'last_visited':
            return computeDefer(6);
        case 'autocomplete_search':
            return computeDefer(7);
        case 'cart_in_header':
            return computeDefer(8);
        case 'menu_iconic':
            return computeDefer(9);
        case 'navigation':
            return computeDefer(10);
        case 'mobile_menu':
            return computeDefer(11);
        case 'newsletter':
            return computeDefer(12);
        case 'user_consent':
            return computeDefer(13);
        case 'gtm_head_script':
            return computeDefer(14);
        case 'gtm_events':
            return computeDefer(16);
        default:
            return computeDefer(20);
    }
};

const computeDefer = (deferWave: number) => DEFER_START + deferWave * DEFER_GAP;

const DEFER_START = 150;
const DEFER_GAP = 70;
