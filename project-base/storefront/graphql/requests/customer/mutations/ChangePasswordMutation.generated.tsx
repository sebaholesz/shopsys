import * as Types from '../../types';

import gql from 'graphql-tag';
import * as Urql from 'urql';
export type Omit<T, K extends keyof T> = Pick<T, Exclude<keyof T, K>>;
export type ChangePasswordMutationVariablesApi = Types.Exact<{
    email: Types.Scalars['String']['input'];
    oldPassword: Types.Scalars['Password']['input'];
    newPassword: Types.Scalars['Password']['input'];
}>;

export type ChangePasswordMutationApi = {
    __typename?: 'Mutation';
    ChangePassword:
        | { __typename?: 'CompanyCustomerUser'; email: string }
        | { __typename?: 'RegularCustomerUser'; email: string };
};

export const ChangePasswordMutationDocumentApi = gql`
    mutation ChangePasswordMutation($email: String!, $oldPassword: Password!, $newPassword: Password!) {
        ChangePassword(input: { email: $email, oldPassword: $oldPassword, newPassword: $newPassword }) {
            email
        }
    }
`;

export function useChangePasswordMutationApi() {
    return Urql.useMutation<ChangePasswordMutationApi, ChangePasswordMutationVariablesApi>(
        ChangePasswordMutationDocumentApi,
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
