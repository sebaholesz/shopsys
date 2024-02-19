import { invalidateFields } from './helpers';
import { Cache, UpdatesConfig } from '@urql/exchange-graphcache';
import {
    AddToCartMutationVariablesApi,
    ApplyPromoCodeToCartMutationVariablesApi,
    CartQueryDocumentApi,
    ChangePaymentInCartMutationVariablesApi,
    ChangeTransportInCartMutationVariablesApi,
    RemoveFromCartMutationVariablesApi,
    RemovePromoCodeFromCartMutationVariablesApi,
    AddOrderItemsToCartMutationVariablesApi,
    RemoveProductListMutationVariablesApi,
    ProductListInputApi,
    MakeMaybe,
    RemoveProductFromListMutationVariablesApi,
    AddProductToListMutationVariablesApi,
    ProductListQueryDocumentApi,
    AddProductToListMutationApi,
    RemoveProductFromListMutationApi,
    ProductListFragmentApi,
    AddToCartMutationApi,
    CartFragmentApi,
    LoginMutationApi,
    LogoutMutationApi,
    DeleteDeliveryAddressMutationApi,
    CreateOrderMutationApi,
    AddOrderItemsToCartMutationApi,
    ChangeTransportInCartMutationApi,
    ChangePaymentInCartMutationApi,
    RemoveFromCartMutationApi,
    ApplyPromoCodeToCartMutationApi,
    RemovePromoCodeFromCartMutationApi,
    LoginMutationVariablesApi,
    LogoutMutationVariablesApi,
    DeleteDeliveryAddressMutationVariablesApi,
    CreateOrderMutationVariablesApi,
    RemoveProductListMutationApi,
    CartQueryApi,
    CartQueryVariablesApi,
    ProductListQueryApi,
    ProductListQueryVariablesApi,
} from 'graphql/generated';

export const cacheUpdates: UpdatesConfig = {
    Mutation: {
        Login(_result: LoginMutationApi, _args: LoginMutationVariablesApi, cache) {
            invalidateFields(cache, ['cart']);
        },
        Logout(_result: LogoutMutationApi, _args: LogoutMutationVariablesApi, cache) {
            invalidateFields(cache, ['cart']);
        },
        DeleteDeliveryAddress(
            _result: DeleteDeliveryAddressMutationApi,
            _args: DeleteDeliveryAddressMutationVariablesApi,
            cache,
        ) {
            invalidateFields(cache, ['currentCustomerUser']);
        },
        CreateOrder(_result: CreateOrderMutationApi, _args: CreateOrderMutationVariablesApi, cache) {
            invalidateFields(cache, ['currentCustomerUser']);
        },
        AddToCart(result: AddToCartMutationApi, _args: AddToCartMutationVariablesApi, cache) {
            manuallyUpdateCartQuery(cache, result.AddToCart.cart, result.AddToCart.cart.uuid);
        },
        AddOrderItemsToCart(
            result: AddOrderItemsToCartMutationApi,
            _args: AddOrderItemsToCartMutationVariablesApi,
            cache,
        ) {
            manuallyUpdateCartQuery(cache, result.AddOrderItemsToCart, result.AddOrderItemsToCart.uuid);
        },
        // Because we use dedup on this mutation, if the mutation is cancelled
        // mid-flight, it calls this updater with the resulting object being null,
        // even though it should not happen
        ChangeTransportInCart(
            result: MakeMaybe<ChangeTransportInCartMutationApi, 'ChangeTransportInCart'>,
            _args: ChangeTransportInCartMutationVariablesApi,
            cache,
        ) {
            if (result.ChangeTransportInCart?.uuid) {
                manuallyUpdateCartQuery(cache, result.ChangeTransportInCart, result.ChangeTransportInCart.uuid);
            }
        },
        // Because we use dedup on this mutation, if the mutation is cancelled
        // mid-flight, it calls this updater with the resulting object being null,
        // even though it should not happen
        ChangePaymentInCart(
            result: MakeMaybe<ChangePaymentInCartMutationApi, 'ChangePaymentInCart'>,
            _args: ChangePaymentInCartMutationVariablesApi,
            cache,
        ) {
            if (result.ChangePaymentInCart?.uuid) {
                manuallyUpdateCartQuery(cache, result.ChangePaymentInCart, result.ChangePaymentInCart.uuid);
            }
        },
        RemoveFromCart(result: RemoveFromCartMutationApi, _args: RemoveFromCartMutationVariablesApi, cache) {
            manuallyUpdateCartQuery(cache, result.RemoveFromCart, result.RemoveFromCart.uuid);
        },
        ApplyPromoCodeToCart(
            result: ApplyPromoCodeToCartMutationApi,
            _args: ApplyPromoCodeToCartMutationVariablesApi,
            cache,
        ) {
            manuallyUpdateCartQuery(cache, result.ApplyPromoCodeToCart, result.ApplyPromoCodeToCart.uuid);
        },
        RemovePromoCodeFromCart(
            result: RemovePromoCodeFromCartMutationApi,
            _args: RemovePromoCodeFromCartMutationVariablesApi,
            cache,
        ) {
            manuallyUpdateCartQuery(cache, result.RemovePromoCodeFromCart, result.RemovePromoCodeFromCart.uuid);
        },
        AddProductToList(result: AddProductToListMutationApi, args: AddProductToListMutationVariablesApi, cache) {
            cache.invalidate('Query');
            manuallyUpdateProductListQuery(args.input.productListInput, result.AddProductToList, cache);
        },
        RemoveProductFromList(
            result: RemoveProductFromListMutationApi,
            args: RemoveProductFromListMutationVariablesApi,
            cache,
        ) {
            if (result.RemoveProductFromList === null) {
                manuallyRemoveProductListQuery(cache, args.input.productListInput);
            } else {
                manuallyUpdateProductListQuery(args.input.productListInput, result.RemoveProductFromList, cache);
            }
        },
        RemoveProductList(_result: RemoveProductListMutationApi, args: RemoveProductListMutationVariablesApi, cache) {
            manuallyRemoveProductListQuery(cache, args.input);
        },
        ChangePaymentInOrder(_result, _args, cache) {
            invalidateFields(cache, ['order']);
        },
    },
};

const manuallyUpdateCartQuery = (cache: Cache, newCart: CartFragmentApi, cartUuid: string | null) => {
    cache.updateQuery<CartQueryApi, CartQueryVariablesApi>(
        { query: CartQueryDocumentApi, variables: { cartUuid } },
        () => ({ __typename: 'Query', cart: newCart }),
    );
};

const manuallyRemoveProductListQuery = (cache: Cache, args: ProductListInputApi) => {
    cache.updateQuery<ProductListQueryApi, ProductListQueryVariablesApi>(
        { query: ProductListQueryDocumentApi, variables: { input: args } },
        () => ({ __typename: 'Query', productList: null }),
    );
};

const manuallyUpdateProductListQuery = (input: ProductListInputApi, result: ProductListFragmentApi, cache: Cache) => {
    const uuid = input.uuid ?? result.uuid;
    cache.updateQuery<ProductListQueryApi, ProductListQueryVariablesApi>(
        {
            query: ProductListQueryDocumentApi,
            variables: {
                input: { type: input.type, uuid },
            },
        },
        () => ({ __typename: 'Query', productList: result }),
    );
};
