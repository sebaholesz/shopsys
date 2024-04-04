import imageLogo from '/public/images/logo.svg';
import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { Image } from 'components/Basic/Image/Image';
import { useDomainConfig } from 'components/providers/DomainConfigProvider';
import { TypeSimpleNotBlogArticleFragment } from 'graphql/requests/articlesInterface/articles/fragments/SimpleNotBlogArticleFragment.generated';
import { useArticlesQuery } from 'graphql/requests/articlesInterface/articles/queries/ArticlesQuery.generated';
import { TypeArticlePlacementTypeEnum } from 'graphql/types';
import useTranslation from 'next-translate/useTranslation';
import { useMemo } from 'react';
import { getInternationalizedStaticUrls } from 'utils/staticUrls/getInternationalizedStaticUrls';

type FooterProps = {
    simpleFooter?: boolean;
};

const dummyData = {
    phone: '+420 111 222 333',
    opening: 'Po - Út, 10 - 16 hod',
};

export const FooterPlaceholder: FC<FooterProps> = ({ simpleFooter }) => {
    const { t } = useTranslation();
    const { url } = useDomainConfig();
    const [cookieConsentUrl, contactUrl] = getInternationalizedStaticUrls(['/cookie-consent', '/contact'], url);
    const [{ data }] = useArticlesQuery({
        variables: {
            placement: [
                TypeArticlePlacementTypeEnum.Footer1,
                TypeArticlePlacementTypeEnum.Footer2,
                TypeArticlePlacementTypeEnum.Footer3,
                TypeArticlePlacementTypeEnum.Footer4,
            ],
            first: 100,
        },
    });

    const items = useMemo(
        () => [
            {
                key: 'about-cc',
                title: t('About Shopsys'),
                items: filterArticlesByPlacement(data?.articles.edges, TypeArticlePlacementTypeEnum.Footer1),
            },
            {
                key: 'about-shopping',
                title: t('About shopping'),
                items: filterArticlesByPlacement(data?.articles.edges, TypeArticlePlacementTypeEnum.Footer2),
            },
            {
                key: 'e-shop',
                title: t('E-shop'),
                items: filterArticlesByPlacement(data?.articles.edges, TypeArticlePlacementTypeEnum.Footer3),
            },
            {
                key: 'stores',
                title: t('Stores'),
                items: filterArticlesByPlacement(data?.articles.edges, TypeArticlePlacementTypeEnum.Footer4),
            },
        ],
        [data?.articles.edges],
    );

    return (
        <>
            {!simpleFooter && (
                <>
                    <a className=" text-white" href={'tel:' + dummyData.phone}>
                        {dummyData.phone}
                    </a>
                    <p>{dummyData.opening}</p>
                    <ExtendedNextLink href={contactUrl}>{t('Write to us')}</ExtendedNextLink>
                    {items.map((item) => (
                        <>
                            <h3 className="text-white">{item.title}</h3>
                            <ul>
                                {item.items.map((item) => (
                                    <li key={item.uuid}>
                                        <ExtendedNextLink
                                            className="text-white"
                                            href={item.__typename === 'ArticleSite' ? item.slug : item.url}
                                            rel={item.external ? 'nofollow noreferrer noopener' : undefined}
                                            target={item.external ? '_blank' : undefined}
                                        >
                                            {item.name}
                                        </ExtendedNextLink>
                                    </li>
                                ))}
                            </ul>
                        </>
                    ))}
                </>
            )}
            {t('Copyright © 2021, Shopsys s.r.o. All rights reserved.')}
            {t('Customized E-shop by')}
            <a className="ml-2 flex w-20" href="https://www.shopsys.com" rel="noreferrer" target="_blank">
                <Image alt="footer logo" src={imageLogo} />
            </a>
            <ExtendedNextLink
                className="self-center text-greyLight no-underline transition hover:text-whitesmoke hover:no-underline"
                href={cookieConsentUrl}
            >
                {t('Cookie consent update')}
            </ExtendedNextLink>
        </>
    );
};

const filterArticlesByPlacement = (
    array: ({ node: TypeSimpleNotBlogArticleFragment | null } | null)[] | undefined | null,
    placement: TypeArticlePlacementTypeEnum,
): TypeSimpleNotBlogArticleFragment[] =>
    array?.reduce(
        (prev, current) => (current?.node?.placement === placement ? [...prev, current.node] : prev),
        [] as TypeSimpleNotBlogArticleFragment[],
    ) ?? [];
