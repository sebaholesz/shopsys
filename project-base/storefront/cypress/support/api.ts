import { RegistrationDataInputApi } from '../../graphql/generated/index';
import 'cypress-real-events';
import 'cypress-set-device-pixel-ratio';
import { products } from 'fixtures/demodata';

Cypress.Commands.add('addProductToCartForTest', (productUuid?: string, quantity?: number) => {
    const currentAppStoreAsString = window.localStorage.getItem('app-store');

    return cy.getCookie('accessToken').then((cookie) => {
        const accessToken = cookie?.value;
        let cartUuid: string | null = null;

        if (!accessToken && currentAppStoreAsString) {
            cartUuid = JSON.parse(currentAppStoreAsString).state.cartUuid;
        }

        return cy
            .request({
                method: 'POST',
                url: 'graphql/',
                body: JSON.stringify({
                    operationName: 'AddToCartMutation',
                    query: `mutation AddToCartMutation($input: AddToCartInput!) { 
                    AddToCart(input: $input) { 
                        cart { 
                            uuid 
                        } 
                    } 
                }`,
                    variables: {
                        input: {
                            cartUuid,
                            productUuid: productUuid ?? products.helloKitty.uuid,
                            quantity: quantity ?? 1,
                        },
                    },
                }),
                headers: {
                    'Content-Type': 'application/json',
                    ...(accessToken ? { 'X-Auth-Token': 'Bearer ' + accessToken } : {}),
                },
            })
            .its('body.data.AddToCart.cart');
    });
});

Cypress.Commands.add('preselectTransportForTest', (transportUuid: string, pickupPlaceIdentifier?: string) => {
    const currentAppStoreAsString = window.localStorage.getItem('app-store');
    if (!currentAppStoreAsString) {
        throw new Error(
            'Could not load app store from local storage. This is an issue with tests, not with the application.',
        );
    }

    return cy.getCookie('accessToken').then((cookie) => {
        const accessToken = cookie?.value;
        let cartUuid: string | null = null;

        if (!accessToken && currentAppStoreAsString) {
            cartUuid = JSON.parse(currentAppStoreAsString).state.cartUuid;
        }

        return cy
            .request({
                method: 'POST',
                url: 'graphql/',
                body: JSON.stringify({
                    operationName: 'ChangeTransportInCartMutation',
                    query: `mutation ChangeTransportInCartMutation($input: ChangeTransportInCartInput!) { 
                    ChangeTransportInCart(input: $input) { 
                        uuid, 
                        transport { 
                            uuid 
                        }, 
                        selectedPickupPlaceIdentifier 
                    } 
                }`,
                    variables: {
                        input: {
                            cartUuid,
                            transportUuid,
                            pickupPlaceIdentifier,
                        },
                    },
                }),
                headers: {
                    'Content-Type': 'application/json',
                    ...(accessToken ? { 'X-Auth-Token': 'Bearer ' + accessToken } : {}),
                },
            })
            .its('body.data.ChangeTransportInCart')
            .then((cart) => {
                expect(cart.uuid).equal(cartUuid);
                expect(cart.transport.uuid).equal(transportUuid);
                if (pickupPlaceIdentifier) {
                    expect(cart.selectedPickupPlaceIdentifier).equal(pickupPlaceIdentifier);
                }
            });
    });
});

Cypress.Commands.add('preselectPaymentForTest', (paymentUuid: string) => {
    const currentAppStoreAsString = window.localStorage.getItem('app-store');
    if (!currentAppStoreAsString) {
        throw new Error(
            'Could not load app store from local storage. This is an issue with tests, not with the application.',
        );
    }

    return cy.getCookie('accessToken').then((cookie) => {
        const accessToken = cookie?.value;
        let cartUuid: string | null = null;

        if (!accessToken && currentAppStoreAsString) {
            cartUuid = JSON.parse(currentAppStoreAsString).state.cartUuid;
        }

        return cy
            .request({
                method: 'POST',
                url: 'graphql/',
                body: JSON.stringify({
                    operationName: 'ChangePaymentInCartMutation',
                    query: `mutation ChangePaymentInCartMutation($input: ChangePaymentInCartInput!) { 
                    ChangePaymentInCart(input: $input) { 
                        uuid, 
                        payment { 
                            uuid 
                        } 
                    } 
                }`,
                    variables: {
                        input: {
                            cartUuid,
                            paymentUuid,
                        },
                    },
                }),
                headers: {
                    'Content-Type': 'application/json',
                    ...(accessToken ? { 'X-Auth-Token': 'Bearer ' + accessToken } : {}),
                },
            })
            .its('body.data.ChangePaymentInCart')
            .then((cart) => {
                expect(cart.uuid).equal(cartUuid);
                expect(cart.payment.uuid).equal(paymentUuid);
            });
    });
});

Cypress.Commands.add('registerAsNewUser', (registrationInput: RegistrationDataInputApi) => {
    return cy
        .request({
            method: 'POST',
            url: 'graphql/',
            body: JSON.stringify({
                operationName: 'RegistrationMutation',
                query: `mutation RegistrationMutation($input: RegistrationDataInput!) {
                    Register(input: $input) {
                      tokens {
                        accessToken
                        refreshToken
                      }
                    }
                  }`,
                variables: {
                    input: registrationInput,
                },
            }),
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .its('body.data.Register')
        .then((registrationResponse) => {
            expect(registrationResponse.tokens.accessToken).to.be.a('string').and.not.be.empty;
            expect(registrationResponse.tokens.refreshToken).to.be.a('string').and.not.be.empty;
            cy.setCookie('accessToken', registrationResponse.tokens.accessToken, { path: '/' });
            cy.setCookie('refreshToken', registrationResponse.tokens.refreshToken, {
                expiry: Math.floor(Date.now() / 1000) + 3600 * 24 * 14,
                path: '/',
            });
        });
});
