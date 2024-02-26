import {
    changeDayOfWeekInApiResponses,
    changeSelectionOfTransportByName,
    chooseTransportPersonalCollectionAndStore,
} from './transportAndPaymentSupport';
import { DEFAULT_APP_STORE, transport, url } from 'fixtures/demodata';
import { generateCustomerRegistrationData } from 'fixtures/generators';
import { checkUrl, takeSnapshotAndCompare } from 'support';
import { TIDs } from 'tids';

describe('Transport select tests', () => {
    beforeEach(() => {
        cy.window().then((win) => {
            win.localStorage.setItem('app-store', JSON.stringify(DEFAULT_APP_STORE));
        });
    });

    it('should select transport to home', () => {
        cy.addProductToCartForTest().then((cart) => cy.storeCartUuidInLocalStorage(cart.uuid));
        cy.visit(url.order.transportAndPayment);

        changeSelectionOfTransportByName(transport.czechPost.name);

        cy.getByTID([TIDs.loader_overlay]).should('not.exist');
        takeSnapshotAndCompare('transport-to-home');
    });

    it('should select personal pickup transport', () => {
        changeDayOfWeekInApiResponses(1);
        cy.addProductToCartForTest().then((cart) => cy.storeCartUuidInLocalStorage(cart.uuid));
        cy.visit(url.order.transportAndPayment);

        chooseTransportPersonalCollectionAndStore(transport.personalCollection.storeOstrava.name);

        cy.getByTID([TIDs.loader_overlay]).should('not.exist');
        takeSnapshotAndCompare('personal-pickup-transport');
    });

    it('should select a transport, deselect it, and then change the transport option', () => {
        cy.addProductToCartForTest().then((cart) => cy.storeCartUuidInLocalStorage(cart.uuid));
        cy.visit(url.order.transportAndPayment);

        changeSelectionOfTransportByName(transport.czechPost.name);
        cy.getByTID([TIDs.loader_overlay]).should('not.exist');
        changeSelectionOfTransportByName(transport.czechPost.name);
        cy.getByTID([TIDs.loader_overlay]).should('not.exist');
        changeSelectionOfTransportByName(transport.ppl.name);
        cy.getByTID([TIDs.loader_overlay]).should('not.exist');

        takeSnapshotAndCompare('select-deselect-and-select-transport-again');
    });

    it('should redirect to cart page and not display transport options if cart is empty and user is not logged in', () => {
        cy.visit(url.order.transportAndPayment);

        cy.getByTID([TIDs.order_content_wrapper_skeleton]).should('exist');

        cy.getByTID([TIDs.cart_page_empty_cart_text]).should('exist');
        checkUrl(url.cart);

        takeSnapshotAndCompare('empty-cart-transport');
    });

    it('should redirect to cart page and not display transport options if cart is empty and user is logged in', () => {
        cy.registerAsNewUser(generateCustomerRegistrationData('commonCustomer'));
        cy.visit(url.order.transportAndPayment);

        cy.getByTID([TIDs.order_content_wrapper_skeleton]).should('exist');

        cy.getByTID([TIDs.cart_page_empty_cart_text]).should('exist');
        checkUrl(url.cart);

        takeSnapshotAndCompare('empty-cart-transport-logged-in');
    });

    it('should change price for transport when cart is large enough for transport to be free', () => {
        // Doprava zdarma v košíku
        // 1. vložit do košíku zboží s nižší hodnotou, než je hodnota pro získání dopravy zdarma
        // 2. v košíku ověřit, že se zobrazuje banner pro dopravu zdarma
        // 3. zvýšit množství v košíku a ověřit, že se částka pro získání dopravy zdarma změnila
        // 4. přejít do 2. kroku objednávky a ověřit, že doprava PPL je placená
        // 5. přejít zpět do košíku a zvýšit množství zboží nad hranici pro získání dopravy zdarma
        // 6. ověřit, že se text v banneru pro získání dopravy zdarma změnil
        // 7. přejí do 2. kroku objednávky a ověřit, že je doprava PPL zdarma
        // 8. zvolit dopravu PPL a ověřit, že je i v souhrnu objednávky zdarma
        // 9. dokončit objednávku
        // 10. na detailu objednávky ověřit, že je doprava PPL zdarma
    });
});
