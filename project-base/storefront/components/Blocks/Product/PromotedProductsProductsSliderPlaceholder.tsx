import { ProductListItemPlaceholder } from './ProductsList/ProductListItemPlaceholder';
import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { usePromotedProductsQuery } from 'graphql/requests/products/queries/PromotedProductsQuery.generated';
import useTranslation from 'next-translate/useTranslation';

export const PromotedProductsProductsSliderPlaceholder: FC = () => {
    const [{ data: promotedProductsData }] = usePromotedProductsQuery();
    const { t } = useTranslation();

    if (!promotedProductsData?.promotedProducts) {
        return null;
    }

    return (
        <div className="relative">
            <div className="absolute -top-11 right-0 hidden items-center justify-center vl:flex ">
                <button
                    disabled
                    className="ml-1 h-8 w-8 cursor-pointer rounded border-none bg-greyDark pt-1 text-creamWhite outline-none transition hover:bg-greyDarker disabled:bg-greyLighter"
                    title={t('Previous products')}
                >
                    <ArrowIcon className="-translate-y-[2px] rotate-90" />
                </button>
                <button
                    className="ml-1 h-8 w-8 cursor-pointer rounded border-none bg-greyDark pt-1 text-creamWhite outline-none transition hover:bg-greyDarker disabled:bg-greyLighter"
                    disabled={promotedProductsData.promotedProducts.length <= 4}
                    title={t('Next products')}
                >
                    <ArrowIcon className="-translate-y-[2px] -rotate-90" />
                </button>
            </div>

            <ul className="grid snap-x snap-mandatory auto-cols-[80%] grid-flow-col overflow-x-auto overscroll-x-contain [-ms-overflow-style:'none'] [scrollbar-width:'none'] md:auto-cols-[45%] lg:auto-cols-[30%] vl:auto-cols-[25%] [&::-webkit-scrollbar]:hidden">
                {promotedProductsData.promotedProducts.map((product, index) =>
                    index < 4 ? (
                        <ProductListItemPlaceholder key={product.uuid} product={product} />
                    ) : (
                        <ExtendedNextLink key={product.uuid} href={product.slug}>
                            {product.fullName}
                        </ExtendedNextLink>
                    ),
                )}
            </ul>
        </div>
    );
};
