import * as Types from '../../types';

import gql from 'graphql-tag';
import { ProductDetailFragmentApi } from '../fragments/ProductDetailFragment.generated';
import { MainVariantDetailFragmentApi } from '../fragments/MainVariantDetailFragment.generated';
import * as Urql from 'urql';
export type Omit<T, K extends keyof T> = Pick<T, Exclude<keyof T, K>>;
export type ProductDetailQueryVariablesApi = Types.Exact<{
    urlSlug: Types.InputMaybe<Types.Scalars['String']['input']>;
}>;

export type ProductDetailQueryApi = {
    __typename?: 'Query';
    product:
        | {
              __typename: 'MainVariant';
              id: number;
              uuid: string;
              slug: string;
              fullName: string;
              name: string;
              namePrefix: string | null;
              nameSuffix: string | null;
              catalogNumber: string;
              ean: string | null;
              description: string | null;
              stockQuantity: number;
              isSellingDenied: boolean;
              seoTitle: string | null;
              seoMetaDescription: string | null;
              isMainVariant: boolean;
              variants: Array<{
                  __typename: 'Variant';
                  id: number;
                  uuid: string;
                  slug: string;
                  fullName: string;
                  name: string;
                  stockQuantity: number;
                  isSellingDenied: boolean;
                  availableStoresCount: number;
                  exposedStoresCount: number;
                  catalogNumber: string;
                  isMainVariant: boolean;
                  storeAvailabilities: Array<{
                      __typename: 'StoreAvailability';
                      exposed: boolean;
                      availabilityInformation: string;
                      availabilityStatus: Types.AvailabilityStatusEnumApi;
                      store: {
                          __typename: 'Store';
                          uuid: string;
                          slug: string;
                          description: string | null;
                          street: string;
                          city: string;
                          postcode: string;
                          contactInfo: string | null;
                          specialMessage: string | null;
                          locationLatitude: string | null;
                          locationLongitude: string | null;
                          storeName: string;
                          country: { __typename: 'Country'; name: string; code: string };
                          openingHours: {
                              __typename?: 'OpeningHours';
                              isOpen: boolean;
                              dayOfWeek: number;
                              openingHoursOfDays: Array<{
                                  __typename?: 'OpeningHoursOfDay';
                                  dayOfWeek: number;
                                  firstOpeningTime: string | null;
                                  firstClosingTime: string | null;
                                  secondOpeningTime: string | null;
                                  secondClosingTime: string | null;
                              }>;
                          };
                          breadcrumb: Array<{ __typename: 'Link'; name: string; slug: string }>;
                          storeImages: Array<{
                              __typename: 'Image';
                              name: string | null;
                              sizes: Array<{
                                  __typename: 'ImageSize';
                                  size: string;
                                  url: string;
                                  width: number | null;
                                  height: number | null;
                                  additionalSizes: Array<{
                                      __typename: 'AdditionalSize';
                                      height: number | null;
                                      media: string;
                                      url: string;
                                      width: number | null;
                                  }>;
                              }>;
                          }>;
                      } | null;
                  }>;
                  flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                  mainImage: {
                      __typename: 'Image';
                      name: string | null;
                      sizes: Array<{
                          __typename: 'ImageSize';
                          size: string;
                          url: string;
                          width: number | null;
                          height: number | null;
                          additionalSizes: Array<{
                              __typename: 'AdditionalSize';
                              height: number | null;
                              media: string;
                              url: string;
                              width: number | null;
                          }>;
                      }>;
                  } | null;
                  price: {
                      __typename: 'ProductPrice';
                      priceWithVat: string;
                      priceWithoutVat: string;
                      vatAmount: string;
                      isPriceFrom: boolean;
                  };
                  availability: { __typename: 'Availability'; name: string; status: Types.AvailabilityStatusEnumApi };
                  brand: { __typename: 'Brand'; name: string; slug: string } | null;
                  categories: Array<{ __typename: 'Category'; name: string }>;
              }>;
              breadcrumb: Array<{ __typename: 'Link'; name: string; slug: string }>;
              images: Array<{
                  __typename: 'Image';
                  name: string | null;
                  sizes: Array<{
                      __typename: 'ImageSize';
                      size: string;
                      url: string;
                      width: number | null;
                      height: number | null;
                      additionalSizes: Array<{
                          __typename: 'AdditionalSize';
                          height: number | null;
                          media: string;
                          url: string;
                          width: number | null;
                      }>;
                  }>;
              }>;
              price: {
                  __typename: 'ProductPrice';
                  priceWithVat: string;
                  priceWithoutVat: string;
                  vatAmount: string;
                  isPriceFrom: boolean;
              };
              parameters: Array<{
                  __typename: 'Parameter';
                  uuid: string;
                  name: string;
                  visible: boolean;
                  values: Array<{ __typename: 'ParameterValue'; uuid: string; text: string }>;
              }>;
              accessories: Array<
                  | {
                        __typename: 'MainVariant';
                        id: number;
                        uuid: string;
                        slug: string;
                        fullName: string;
                        name: string;
                        stockQuantity: number;
                        isSellingDenied: boolean;
                        availableStoresCount: number;
                        exposedStoresCount: number;
                        catalogNumber: string;
                        isMainVariant: boolean;
                        flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                        mainImage: {
                            __typename: 'Image';
                            name: string | null;
                            sizes: Array<{
                                __typename: 'ImageSize';
                                size: string;
                                url: string;
                                width: number | null;
                                height: number | null;
                                additionalSizes: Array<{
                                    __typename: 'AdditionalSize';
                                    height: number | null;
                                    media: string;
                                    url: string;
                                    width: number | null;
                                }>;
                            }>;
                        } | null;
                        price: {
                            __typename: 'ProductPrice';
                            priceWithVat: string;
                            priceWithoutVat: string;
                            vatAmount: string;
                            isPriceFrom: boolean;
                        };
                        availability: {
                            __typename: 'Availability';
                            name: string;
                            status: Types.AvailabilityStatusEnumApi;
                        };
                        brand: { __typename: 'Brand'; name: string; slug: string } | null;
                        categories: Array<{ __typename: 'Category'; name: string }>;
                    }
                  | {
                        __typename: 'RegularProduct';
                        id: number;
                        uuid: string;
                        slug: string;
                        fullName: string;
                        name: string;
                        stockQuantity: number;
                        isSellingDenied: boolean;
                        availableStoresCount: number;
                        exposedStoresCount: number;
                        catalogNumber: string;
                        isMainVariant: boolean;
                        flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                        mainImage: {
                            __typename: 'Image';
                            name: string | null;
                            sizes: Array<{
                                __typename: 'ImageSize';
                                size: string;
                                url: string;
                                width: number | null;
                                height: number | null;
                                additionalSizes: Array<{
                                    __typename: 'AdditionalSize';
                                    height: number | null;
                                    media: string;
                                    url: string;
                                    width: number | null;
                                }>;
                            }>;
                        } | null;
                        price: {
                            __typename: 'ProductPrice';
                            priceWithVat: string;
                            priceWithoutVat: string;
                            vatAmount: string;
                            isPriceFrom: boolean;
                        };
                        availability: {
                            __typename: 'Availability';
                            name: string;
                            status: Types.AvailabilityStatusEnumApi;
                        };
                        brand: { __typename: 'Brand'; name: string; slug: string } | null;
                        categories: Array<{ __typename: 'Category'; name: string }>;
                    }
                  | {
                        __typename: 'Variant';
                        id: number;
                        uuid: string;
                        slug: string;
                        fullName: string;
                        name: string;
                        stockQuantity: number;
                        isSellingDenied: boolean;
                        availableStoresCount: number;
                        exposedStoresCount: number;
                        catalogNumber: string;
                        isMainVariant: boolean;
                        flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                        mainImage: {
                            __typename: 'Image';
                            name: string | null;
                            sizes: Array<{
                                __typename: 'ImageSize';
                                size: string;
                                url: string;
                                width: number | null;
                                height: number | null;
                                additionalSizes: Array<{
                                    __typename: 'AdditionalSize';
                                    height: number | null;
                                    media: string;
                                    url: string;
                                    width: number | null;
                                }>;
                            }>;
                        } | null;
                        price: {
                            __typename: 'ProductPrice';
                            priceWithVat: string;
                            priceWithoutVat: string;
                            vatAmount: string;
                            isPriceFrom: boolean;
                        };
                        availability: {
                            __typename: 'Availability';
                            name: string;
                            status: Types.AvailabilityStatusEnumApi;
                        };
                        brand: { __typename: 'Brand'; name: string; slug: string } | null;
                        categories: Array<{ __typename: 'Category'; name: string }>;
                    }
              >;
              brand: { __typename: 'Brand'; name: string; slug: string } | null;
              categories: Array<{ __typename?: 'Category'; name: string }>;
              flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
              availability: { __typename: 'Availability'; name: string; status: Types.AvailabilityStatusEnumApi };
              productVideos: Array<{ __typename: 'VideoToken'; description: string; token: string }>;
          }
        | {
              __typename: 'RegularProduct';
              shortDescription: string | null;
              availableStoresCount: number;
              exposedStoresCount: number;
              id: number;
              uuid: string;
              slug: string;
              fullName: string;
              name: string;
              namePrefix: string | null;
              nameSuffix: string | null;
              catalogNumber: string;
              ean: string | null;
              description: string | null;
              stockQuantity: number;
              isSellingDenied: boolean;
              seoTitle: string | null;
              seoMetaDescription: string | null;
              isMainVariant: boolean;
              storeAvailabilities: Array<{
                  __typename: 'StoreAvailability';
                  exposed: boolean;
                  availabilityInformation: string;
                  availabilityStatus: Types.AvailabilityStatusEnumApi;
                  store: {
                      __typename: 'Store';
                      uuid: string;
                      slug: string;
                      description: string | null;
                      street: string;
                      city: string;
                      postcode: string;
                      contactInfo: string | null;
                      specialMessage: string | null;
                      locationLatitude: string | null;
                      locationLongitude: string | null;
                      storeName: string;
                      country: { __typename: 'Country'; name: string; code: string };
                      openingHours: {
                          __typename?: 'OpeningHours';
                          isOpen: boolean;
                          dayOfWeek: number;
                          openingHoursOfDays: Array<{
                              __typename?: 'OpeningHoursOfDay';
                              dayOfWeek: number;
                              firstOpeningTime: string | null;
                              firstClosingTime: string | null;
                              secondOpeningTime: string | null;
                              secondClosingTime: string | null;
                          }>;
                      };
                      breadcrumb: Array<{ __typename: 'Link'; name: string; slug: string }>;
                      storeImages: Array<{
                          __typename: 'Image';
                          name: string | null;
                          sizes: Array<{
                              __typename: 'ImageSize';
                              size: string;
                              url: string;
                              width: number | null;
                              height: number | null;
                              additionalSizes: Array<{
                                  __typename: 'AdditionalSize';
                                  height: number | null;
                                  media: string;
                                  url: string;
                                  width: number | null;
                              }>;
                          }>;
                      }>;
                  } | null;
              }>;
              breadcrumb: Array<{ __typename: 'Link'; name: string; slug: string }>;
              images: Array<{
                  __typename: 'Image';
                  name: string | null;
                  sizes: Array<{
                      __typename: 'ImageSize';
                      size: string;
                      url: string;
                      width: number | null;
                      height: number | null;
                      additionalSizes: Array<{
                          __typename: 'AdditionalSize';
                          height: number | null;
                          media: string;
                          url: string;
                          width: number | null;
                      }>;
                  }>;
              }>;
              price: {
                  __typename: 'ProductPrice';
                  priceWithVat: string;
                  priceWithoutVat: string;
                  vatAmount: string;
                  isPriceFrom: boolean;
              };
              parameters: Array<{
                  __typename: 'Parameter';
                  uuid: string;
                  name: string;
                  visible: boolean;
                  values: Array<{ __typename: 'ParameterValue'; uuid: string; text: string }>;
              }>;
              accessories: Array<
                  | {
                        __typename: 'MainVariant';
                        id: number;
                        uuid: string;
                        slug: string;
                        fullName: string;
                        name: string;
                        stockQuantity: number;
                        isSellingDenied: boolean;
                        availableStoresCount: number;
                        exposedStoresCount: number;
                        catalogNumber: string;
                        isMainVariant: boolean;
                        flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                        mainImage: {
                            __typename: 'Image';
                            name: string | null;
                            sizes: Array<{
                                __typename: 'ImageSize';
                                size: string;
                                url: string;
                                width: number | null;
                                height: number | null;
                                additionalSizes: Array<{
                                    __typename: 'AdditionalSize';
                                    height: number | null;
                                    media: string;
                                    url: string;
                                    width: number | null;
                                }>;
                            }>;
                        } | null;
                        price: {
                            __typename: 'ProductPrice';
                            priceWithVat: string;
                            priceWithoutVat: string;
                            vatAmount: string;
                            isPriceFrom: boolean;
                        };
                        availability: {
                            __typename: 'Availability';
                            name: string;
                            status: Types.AvailabilityStatusEnumApi;
                        };
                        brand: { __typename: 'Brand'; name: string; slug: string } | null;
                        categories: Array<{ __typename: 'Category'; name: string }>;
                    }
                  | {
                        __typename: 'RegularProduct';
                        id: number;
                        uuid: string;
                        slug: string;
                        fullName: string;
                        name: string;
                        stockQuantity: number;
                        isSellingDenied: boolean;
                        availableStoresCount: number;
                        exposedStoresCount: number;
                        catalogNumber: string;
                        isMainVariant: boolean;
                        flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                        mainImage: {
                            __typename: 'Image';
                            name: string | null;
                            sizes: Array<{
                                __typename: 'ImageSize';
                                size: string;
                                url: string;
                                width: number | null;
                                height: number | null;
                                additionalSizes: Array<{
                                    __typename: 'AdditionalSize';
                                    height: number | null;
                                    media: string;
                                    url: string;
                                    width: number | null;
                                }>;
                            }>;
                        } | null;
                        price: {
                            __typename: 'ProductPrice';
                            priceWithVat: string;
                            priceWithoutVat: string;
                            vatAmount: string;
                            isPriceFrom: boolean;
                        };
                        availability: {
                            __typename: 'Availability';
                            name: string;
                            status: Types.AvailabilityStatusEnumApi;
                        };
                        brand: { __typename: 'Brand'; name: string; slug: string } | null;
                        categories: Array<{ __typename: 'Category'; name: string }>;
                    }
                  | {
                        __typename: 'Variant';
                        id: number;
                        uuid: string;
                        slug: string;
                        fullName: string;
                        name: string;
                        stockQuantity: number;
                        isSellingDenied: boolean;
                        availableStoresCount: number;
                        exposedStoresCount: number;
                        catalogNumber: string;
                        isMainVariant: boolean;
                        flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
                        mainImage: {
                            __typename: 'Image';
                            name: string | null;
                            sizes: Array<{
                                __typename: 'ImageSize';
                                size: string;
                                url: string;
                                width: number | null;
                                height: number | null;
                                additionalSizes: Array<{
                                    __typename: 'AdditionalSize';
                                    height: number | null;
                                    media: string;
                                    url: string;
                                    width: number | null;
                                }>;
                            }>;
                        } | null;
                        price: {
                            __typename: 'ProductPrice';
                            priceWithVat: string;
                            priceWithoutVat: string;
                            vatAmount: string;
                            isPriceFrom: boolean;
                        };
                        availability: {
                            __typename: 'Availability';
                            name: string;
                            status: Types.AvailabilityStatusEnumApi;
                        };
                        brand: { __typename: 'Brand'; name: string; slug: string } | null;
                        categories: Array<{ __typename: 'Category'; name: string }>;
                    }
              >;
              brand: { __typename: 'Brand'; name: string; slug: string } | null;
              categories: Array<{ __typename?: 'Category'; name: string }>;
              flags: Array<{ __typename: 'Flag'; uuid: string; name: string; rgbColor: string }>;
              availability: { __typename: 'Availability'; name: string; status: Types.AvailabilityStatusEnumApi };
              productVideos: Array<{ __typename: 'VideoToken'; description: string; token: string }>;
          }
        | { __typename?: 'Variant'; mainVariant: { __typename?: 'MainVariant'; slug: string } | null }
        | null;
};

export const ProductDetailQueryDocumentApi = gql`
    query ProductDetailQuery($urlSlug: String) {
        product(urlSlug: $urlSlug) {
            ... on Product {
                ...ProductDetailFragment
            }
            ... on MainVariant {
                ...MainVariantDetailFragment
            }
            ... on Variant {
                mainVariant {
                    slug
                }
            }
        }
    }
    ${ProductDetailFragmentApi}
    ${MainVariantDetailFragmentApi}
`;

export function useProductDetailQueryApi(options?: Omit<Urql.UseQueryArgs<ProductDetailQueryVariablesApi>, 'query'>) {
    return Urql.useQuery<ProductDetailQueryApi, ProductDetailQueryVariablesApi>({
        query: ProductDetailQueryDocumentApi,
        ...options,
    });
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
