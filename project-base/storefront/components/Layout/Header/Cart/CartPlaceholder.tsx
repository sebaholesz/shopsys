import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { CartIcon } from 'components/Basic/Icon/CartIcon';
import { Loader } from 'components/Basic/Loader/Loader';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { twJoin } from 'tailwind-merge';
import { useFormatPrice } from 'utils/formatting/useFormatPrice';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

export const CartPlaceholder: FC = () => {
    const { url } = useDomainConfig();
    const [cartUrl] = getInternationalizedStaticUrls(['/cart'], url);
    const formatPrice = useFormatPrice();

    return (
        <div className="group relative lg:flex order-3 vl:order-4">
            <Loader className="absolute inset-0 z-overlay flex h-full w-full items-center justify-center rounded bg-greyLighter py-2 opacity-50" />

            <ExtendedNextLink
                href={cartUrl}
                className={twJoin(
                    'hidden items-center gap-x-4 rounded bg-orangeLight py-4 pr-2 pl-4 text-black no-underline transition-all hover:text-black hover:no-underline group-hover:rounded-b-none group-hover:bg-white group-hover:shadow-lg lg:flex',
                )}
            >
                <span className="relative flex text-lg">
                    <CartIcon className="w-6 lg:w-5" />
                    <span className="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-xs font-bold leading-normal text-white lg:-top-2 lg:-right-2">
                        0
                    </span>
                </span>
                <span className="hidden text-sm font-bold lg:block">
                    {formatPrice(0, {
                        explicitZero: true,
                    })}
                </span>
            </ExtendedNextLink>

            <div className="flex cursor-pointer items-center justify-center text-lg outline-none lg:hidden">
                <ExtendedNextLink
                    className="relative flex h-full w-full items-center justify-center p-3 text-white no-underline transition-colors hover:text-white hover:no-underline"
                    href={cartUrl}
                >
                    <CartIcon className="w-6 text-white" />
                    <span className="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-xs font-bold leading-normal text-white lg:-top-2 lg:-right-2">
                        0
                    </span>
                </ExtendedNextLink>
            </div>
        </div>
    );
};
