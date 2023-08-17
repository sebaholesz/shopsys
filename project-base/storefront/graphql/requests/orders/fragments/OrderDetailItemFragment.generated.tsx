import * as Types from '../../types';

import gql from 'graphql-tag';
import { PriceFragmentApi } from '../../prices/fragments/PriceFragment.generated';
export type OrderDetailItemFragmentApi = {
    __typename: 'OrderItem';
    name: string;
    vatRate: string;
    quantity: number;
    unit: string | null;
    unitPrice: { __typename: 'Price'; priceWithVat: string; priceWithoutVat: string; vatAmount: string };
    totalPrice: { __typename: 'Price'; priceWithVat: string; priceWithoutVat: string; vatAmount: string };
};

export const OrderDetailItemFragmentApi = gql`
    fragment OrderDetailItemFragment on OrderItem {
        __typename
        name
        unitPrice {
            ...PriceFragment
        }
        totalPrice {
            ...PriceFragment
        }
        vatRate
        quantity
        unit
    }
    ${PriceFragmentApi}
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
