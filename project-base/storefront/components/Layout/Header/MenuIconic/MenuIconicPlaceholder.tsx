import { MenuIconicItem, MenuIconicItemLink } from './MenuIconicElements';
import { CompareIcon } from 'components/Basic/Icon/CompareIcon';
import { HeartIcon } from 'components/Basic/Icon/HeartIcon';
import { MarkerIcon } from 'components/Basic/Icon/MarkerIcon';
import { UserIcon } from 'components/Basic/Icon/UserIcon';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import useTranslation from 'next-translate/useTranslation';
import { useIsUserLoggedIn } from 'utils/auth/useIsUserLoggedIn';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

export const MenuIconicPlaceholder: FC = () => {
    const { t } = useTranslation();
    const { url } = useDomainConfig();
    const [storesUrl, productComparisonUrl, wishlistUrl, customerUrl] = getInternationalizedStaticUrls(
        ['/stores', '/product-comparison', '/wishlist', '/customer'],
        url,
    );

    const isUserLoggedIn = useIsUserLoggedIn();

    return (
        <ul className="flex items-center gap-1">
            <MenuIconicItem className="max-lg:hidden">
                <MenuIconicItemLink href={storesUrl} type="stores">
                    <MarkerIcon className="w-4 text-white" />
                    {t('Stores')}
                </MenuIconicItemLink>
            </MenuIconicItem>

            <MenuIconicItem className="relative">
                {isUserLoggedIn ? (
                    <div className="group">
                        <MenuIconicItemLink
                            className="rounded-t p-3 group-hover:bg-white group-hover:text-dark max-lg:hidden"
                            href={customerUrl}
                        >
                            <UserIcon className="w-4 text-white group-hover:text-dark" />
                            {t('My account')}
                        </MenuIconicItemLink>
                    </div>
                ) : (
                    <MenuIconicItemLink>
                        <UserIcon className="w-5 lg:w-4" />
                        <span className="hidden lg:inline-block">{t('Login')}</span>
                    </MenuIconicItemLink>
                )}
            </MenuIconicItem>

            <MenuIconicItem className="max-lg:hidden">
                <MenuIconicItemLink href={productComparisonUrl} title={t('Comparison')} type="comparison">
                    <CompareIcon className="w-4 text-white" />
                </MenuIconicItemLink>
            </MenuIconicItem>

            <MenuIconicItem className="max-lg:hidden">
                <MenuIconicItemLink href={wishlistUrl} title={t('Wishlist')} type="wishlist">
                    <HeartIcon className="w-4 text-white" isFull={false} />
                </MenuIconicItemLink>
            </MenuIconicItem>
        </ul>
    );
};
