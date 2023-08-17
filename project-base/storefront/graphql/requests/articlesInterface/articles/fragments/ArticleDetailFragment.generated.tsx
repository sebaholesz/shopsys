import * as Types from '../../../types';

import gql from 'graphql-tag';
import { BreadcrumbFragmentApi } from '../../../breadcrumbs/fragments/BreadcrumbFragment.generated';
export type ArticleDetailFragmentApi = {
    __typename: 'ArticleSite';
    uuid: string;
    slug: string;
    placement: string;
    text: string | null;
    seoTitle: string | null;
    seoMetaDescription: string | null;
    createdAt: any;
    articleName: string;
    breadcrumb: Array<{ __typename: 'Link'; name: string; slug: string }>;
};

export const ArticleDetailFragmentApi = gql`
    fragment ArticleDetailFragment on ArticleSite {
        __typename
        uuid
        slug
        placement
        articleName: name
        text
        breadcrumb {
            ...BreadcrumbFragment
        }
        seoTitle
        seoMetaDescription
        createdAt
    }
    ${BreadcrumbFragmentApi}
`;

export interface PossibleTypesResultData {
    possibleTypes: {
        [key: string]: string[];
    };
}
const result: PossibleTypesResultData = {
    possibleTypes: {
        Advert: ['AdvertCode', 'AdvertImage'],
        ArticleInterface: ['ArticleSite', 'BlogArticle'],
        Breadcrumb: [
            'ArticleSite',
            'BlogArticle',
            'BlogCategory',
            'Brand',
            'Category',
            'Flag',
            'MainVariant',
            'RegularProduct',
            'Store',
            'Variant',
        ],
        CartInterface: ['Cart'],
        CustomerUser: ['CompanyCustomerUser', 'RegularCustomerUser'],
        NotBlogArticleInterface: ['ArticleLink', 'ArticleSite'],
        ParameterFilterOptionInterface: [
            'ParameterCheckboxFilterOption',
            'ParameterColorFilterOption',
            'ParameterSliderFilterOption',
        ],
        PriceInterface: ['Price', 'ProductPrice'],
        Product: ['MainVariant', 'RegularProduct', 'Variant'],
        ProductListable: ['Brand', 'Category', 'Flag'],
        Slug: [
            'ArticleSite',
            'BlogArticle',
            'BlogCategory',
            'Brand',
            'Category',
            'Flag',
            'MainVariant',
            'RegularProduct',
            'Store',
            'Variant',
        ],
    },
};
export default result;
