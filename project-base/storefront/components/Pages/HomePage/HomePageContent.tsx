import { SearchMetadata } from 'components/Basic/Head/SearchMetadata';
import { DeferredBannersSlider } from 'components/Blocks/Banners/DeferredBannersSlider';
import { DeferredBlogPreview } from 'components/Blocks/BlogPreview/DeferredBlogPreview';
import { PromotedCategories } from 'components/Blocks/Categories/PromotedCategories';
import { DeferredPromotedProducts } from 'components/Blocks/Product/DeferredPromotedProducts';
import { DeferredLastVisitedProducts } from 'components/Blocks/Product/LastVisitedProducts/DeferredLastVisitedProducts';
import { CommonLayout } from 'components/Layout/CommonLayout';
import { Webline } from 'components/Layout/Webline/Webline';
import { DeferredGtmEvents } from 'components/Pages/HomePage/DeferredGtmEvents';
import useTranslation from 'next-translate/useTranslation';

export const HomePageContent: FC = () => {
    const { t } = useTranslation();

    return (
        <>
            <DeferredGtmEvents />
            <SearchMetadata />
            <CommonLayout>
                <Webline className="mb-14">
                    <DeferredBannersSlider />
                </Webline>

                <Webline className="mb-6">
                    <h2 className="mb-3">{t('Promoted categories')}</h2>
                    <PromotedCategories />
                </Webline>

                <Webline className="mb-6">
                    <h2 className="mb-3">{t('Promoted products')}</h2>
                    <DeferredPromotedProducts />
                </Webline>

                <Webline type="blog">
                    <DeferredBlogPreview />
                </Webline>

                <DeferredLastVisitedProducts />
            </CommonLayout>
        </>
    );
};
