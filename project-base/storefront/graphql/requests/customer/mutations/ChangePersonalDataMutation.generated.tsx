import * as Types from '../../types';

import gql from 'graphql-tag';
import { CustomerUserFragmentApi } from '../fragments/CustomerUserFragment.generated';
import * as Urql from 'urql';
export type Omit<T, K extends keyof T> = Pick<T, Exclude<keyof T, K>>;
export type ChangePersonalDataMutationVariablesApi = Types.Exact<{
    input: Types.ChangePersonalDataInputApi;
}>;

export type ChangePersonalDataMutationApi = {
    __typename?: 'Mutation';
    ChangePersonalData:
        | {
              __typename: 'CompanyCustomerUser';
              companyName: string | null;
              companyNumber: string | null;
              companyTaxNumber: string | null;
              uuid: string;
              firstName: string;
              lastName: string;
              email: string;
              telephone: string | null;
              street: string;
              city: string;
              postcode: string;
              newsletterSubscription: boolean;
              pricingGroup: string;
              country: { __typename: 'Country'; name: string; code: string };
              defaultDeliveryAddress: {
                  __typename: 'DeliveryAddress';
                  uuid: string;
                  companyName: string | null;
                  street: string | null;
                  city: string | null;
                  postcode: string | null;
                  telephone: string | null;
                  firstName: string | null;
                  lastName: string | null;
                  country: { __typename: 'Country'; name: string; code: string } | null;
              } | null;
              deliveryAddresses: Array<{
                  __typename: 'DeliveryAddress';
                  uuid: string;
                  companyName: string | null;
                  street: string | null;
                  city: string | null;
                  postcode: string | null;
                  telephone: string | null;
                  firstName: string | null;
                  lastName: string | null;
                  country: { __typename: 'Country'; name: string; code: string } | null;
              }>;
          }
        | {
              __typename: 'RegularCustomerUser';
              uuid: string;
              firstName: string;
              lastName: string;
              email: string;
              telephone: string | null;
              street: string;
              city: string;
              postcode: string;
              newsletterSubscription: boolean;
              pricingGroup: string;
              country: { __typename: 'Country'; name: string; code: string };
              defaultDeliveryAddress: {
                  __typename: 'DeliveryAddress';
                  uuid: string;
                  companyName: string | null;
                  street: string | null;
                  city: string | null;
                  postcode: string | null;
                  telephone: string | null;
                  firstName: string | null;
                  lastName: string | null;
                  country: { __typename: 'Country'; name: string; code: string } | null;
              } | null;
              deliveryAddresses: Array<{
                  __typename: 'DeliveryAddress';
                  uuid: string;
                  companyName: string | null;
                  street: string | null;
                  city: string | null;
                  postcode: string | null;
                  telephone: string | null;
                  firstName: string | null;
                  lastName: string | null;
                  country: { __typename: 'Country'; name: string; code: string } | null;
              }>;
          };
};

export const ChangePersonalDataMutationDocumentApi = gql`
    mutation ChangePersonalDataMutation($input: ChangePersonalDataInput!) {
        ChangePersonalData(input: $input) {
            ...CustomerUserFragment
        }
    }
    ${CustomerUserFragmentApi}
`;

export function useChangePersonalDataMutationApi() {
    return Urql.useMutation<ChangePersonalDataMutationApi, ChangePersonalDataMutationVariablesApi>(
        ChangePersonalDataMutationDocumentApi,
    );
}

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
