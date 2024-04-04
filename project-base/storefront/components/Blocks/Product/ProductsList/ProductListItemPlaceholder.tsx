import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { CartIcon } from 'components/Basic/Icon/CartIcon';
import { CompareIcon } from 'components/Basic/Icon/CompareIcon';
import { HeartIcon } from 'components/Basic/Icon/HeartIcon';
import { Image } from 'components/Basic/Image/Image';
import { ProductAvailableStoresCount } from 'components/Blocks/Product/ProductAvailableStoresCount';
import { ProductFlags } from 'components/Blocks/Product/ProductFlags';
import { ProductPrice } from 'components/Blocks/Product/ProductPrice';
import { Button } from 'components/Forms/Button/Button';
import { TIDs } from 'cypress/tids';
import { TypeListedProductFragment } from 'graphql/requests/products/fragments/ListedProductFragment.generated';
import useTranslation from 'next-translate/useTranslation';
import { twJoin } from 'tailwind-merge';
import { FunctionComponentProps } from 'types/globals';
import { twMergeCustom } from 'utils/twMerge';

type ProductItemProps = {
    product: TypeListedProductFragment;
} & FunctionComponentProps;

export const ProductListItemPlaceholder: FC<ProductItemProps> = ({ product, className }) => {
    return (
        <li
            tid={TIDs.blocks_product_list_listeditem_ + product.catalogNumber}
            className={twMergeCustom(
                'relative flex select-none flex-col justify-between gap-3 border-b border-greyLighter p-3 text-left lg:hover:z-above lg:hover:bg-white lg:hover:shadow-xl',
                className,
            )}
        >
            <ExtendedNextLink
                className="flex h-full select-none flex-col gap-3 no-underline hover:no-underline"
                draggable={false}
                href={product.slug}
                type={product.isMainVariant ? 'productMainVariant' : 'product'}
            >
                <div className="relative flex h-56 items-center justify-center">
                    <Image
                        alt={product.mainImage?.name || product.fullName}
                        className="max-h-full object-contain"
                        draggable={false}
                        height={250}
                        src={product.mainImage?.url}
                        width={250}
                    />

                    {!!product.flags.length && (
                        <div className="absolute top-3 left-4 flex flex-col">
                            <ProductFlags flags={product.flags} />
                        </div>
                    )}
                </div>

                <div className="h-10 overflow-hidden text-lg font-bold leading-5 text-dark">{product.fullName}</div>

                <ProductPrice productPrice={product.price} />

                <div className="flex flex-col gap-1 text-sm text-black">
                    <div>{product.availability.name}</div>
                    <ProductAvailableStoresCount
                        availableStoresCount={product.availableStoresCount}
                        isMainVariant={product.isMainVariant}
                    />
                </div>
            </ExtendedNextLink>

            <div className="flex justify-end gap-2">
                <div className="flex cursor-pointer items-center gap-2 p-2">
                    <CompareIcon className="text-grey" />
                </div>
                <div className="flex cursor-pointer items-center gap-2 p-2">
                    <HeartIcon className="text-grey" isFull={false} />
                </div>
            </div>

            <ProductActionPlaceholder product={product} />
        </li>
    );
};

type ProductActionProps = {
    product: TypeListedProductFragment;
};

const wrapperTwClass = 'rounded bg-greyVeryLight p-2';

const ProductActionPlaceholder: FC<ProductActionProps> = ({ product }) => {
    const { t } = useTranslation();

    if (product.isMainVariant) {
        return (
            <div className={wrapperTwClass}>
                <ExtendedNextLink href={product.slug} type="productMainVariant">
                    <Button className="w-full py-2" name="choose-variant" size="small">
                        {t('Choose variant')}
                    </Button>
                </ExtendedNextLink>
            </div>
        );
    }

    if (product.isSellingDenied) {
        return <div className={twJoin('text-center', wrapperTwClass)}>{t('This item can no longer be purchased')}</div>;
    }

    return (
        <div className={twJoin('flex items-stretch justify-between gap-2', wrapperTwClass)}>
            <div className="inline-flex w-20 overflow-hidden rounded border-2 border-border bg-white [&>button]:translate-y-0 [&>button]:text-xs">
                <div
                    className="flex min-h-0 w-6 cursor-pointer items-center justify-center border-none bg-none p-0 text-2xl text-dark outline-none"
                    title={t('Decrease')}
                >
                    -
                </div>
                <input
                    aria-label={`${t('Quantity')} ${product.uuid}`}
                    className="h-full min-w-0 flex-1 border-0 p-0 text-center text-lg font-bold text-dark outline-none"
                    type="number"
                />
                <div
                    className="flex min-h-0 w-6 cursor-pointer items-center justify-center border-none bg-none p-0 text-2xl text-dark outline-none"
                    title={t('Increase')}
                >
                    +
                </div>
            </div>
            <Button className="py-2" isDisabled={false} size="small">
                <CartIcon className="w-4 text-white" />
                <span>{t('Add to cart')}</span>
            </Button>
        </div>
    );
};
