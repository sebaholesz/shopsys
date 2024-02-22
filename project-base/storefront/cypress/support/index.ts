import { RegistrationDataInputApi } from '../../graphql/generated/index';
import 'cypress-real-events';
import 'cypress-set-device-pixel-ratio';
import compareSnapshotCommand from 'cypress-visual-regression/dist/command';
import { DEFAULT_APP_STORE, products } from 'fixtures/demodata';
import { TIDs } from 'tids';

Cypress.Commands.add('getByTID', (selectors: ([TIDs, number] | TIDs)[]) => {
    let selectorString = '';
    for (const selector of selectors) {
        if (Array.isArray(selector)) {
            const [selectorPrefix, index] = selector;
            selectorString += `[tid=${selectorPrefix}${index}] `;
        } else {
            selectorString += `[tid=${selector}] `;
        }
    }

    return cy.get(selectorString.trim());
});

Cypress.Commands.add('storeCartUuidInLocalStorage', (cartUuid: string) => {
    return cy.then(() => {
        const currentAppStoreAsString = window.localStorage.getItem('app-store');
        let currentAppStore = DEFAULT_APP_STORE;
        if (currentAppStoreAsString) {
            currentAppStore = JSON.parse(currentAppStoreAsString);
        }
        currentAppStore.state.cartUuid = cartUuid;

        window.localStorage.setItem('app-store', JSON.stringify(currentAppStore));
    });
});

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

compareSnapshotCommand({
    capture: 'fullPage',
});

export const checkAndHideSuccessToast = () => {
    cy.getByTID([TIDs.toast_success]).should('exist').click().should('not.exist');
};

export const checkUrl = (url: string) => {
    cy.url().should('contain', url);
};

export const checkLoaderOverlayIsNotVisible = (timeout?: number) => {
    cy.getByTID([TIDs.loader_overlay]).should('be.visible', { timeout });
};

export const takeSnapshotAndCompare = (snapshotName: string) => {
    cy.wait(200);
    cy.setDevicePixelRatio(1);
    cy.screenshot();
    cy.compareSnapshot(snapshotName);
};

export const changeElementText = (selector: TIDs, newText: string, isRightAfterSSR = true) => {
    if (isRightAfterSSR) {
        cy.wait(200);
    }
    cy.getByTID([selector]).then((element) => {
        element.text(newText);
    });
};

export const loseFocus = () => {
    cy.focused().blur();
};
