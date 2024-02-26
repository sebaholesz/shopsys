import {
    increaseCartItemQuantityWithSpinbox,
    checkCartItemTotalPrice,
    checkCartTotalPrice,
    decreaseCartItemQuantityWithSpinbox,
    continueToTransportAndPaymentSelection,
} from './cartSupport';
import { products, url } from 'fixtures/demodata';
import { checkLoaderOverlayIsNotVisible, checkUrl } from 'support';
import { TIDs } from 'tids';

describe('Cart login tests', () => {
    beforeEach(() => {
        cy.addProductToCartForTest(undefined, 2).then((cart) => cy.storeCartUuidInLocalStorage(cart.uuid));
        cy.addProductToCartForTest(products.philips32PFL4308.uuid);
        cy.visit(url.cart);
    });

    it('should empty cart after log out', () => {
        // Kontrola vyprázdnění košíku po odhlášení
        // 1. uživatel se přihlásí
        // 2. kontrola úspěšného přihlášní
        // 3. uživatel přidá produkt do košíku
        // 4. kontrola produktu v košíku
        // 5. odhlášení uživatele
        // 6. kontrola úspěšného odhlášení
        // 7. kontrola prázdného košíku
    });

    it('should repeatedly merge carts when logged in (starting with empty cart)', () => {
        // Kontrola spojení košíků po přihlášení
        // 1. jako nepřihlášený uživatel přidat produkt A do košíku
        // 2. přihlášení za uživatele (předpoklad, že uživatel měl dříve prázdný košík)
        // 3. po přihlášení kontrola produktu A v košíku za přihlášeného uživatele
        // 4. odhlášení
        // 5. kontrola prázdného košíku
        // 6. přidat do košíku produkt B
        // 7. znovu přihlášení za uživatele X
        // 8. kontrola produktu A a B v košíku za uživatele X
    });

    it("should discard user's previous cart after logging in in order 3rd step", () => {
        // Kontrola obsahu košíku po přihlášení v objednávce (zahození starého košíku uživatele)
        // 1. jako nepřihlášený uživatel přidat produkt A do košíku
        // 2. přihlášení za uživatele (předpoklad, že uživatel měl dříve prázdný košík)
        // 3. po přihlášení kontrola produktu A v košíku za přihlášeného uživatele
        // 4. odhlášení
        // 5. kontrola prázdného košíku
        // 6. přidat do košíku produkt B
        // 7. přejít do objednávky do 3. kroku a přihlásit se za uživatele X
        // 8. po přihlášení ověřit, že v košíku zůstal produkt B a košík neobsahuje produkt A (který měl uživatel dříve v košíku)
        // 9. ověřit že došlo k přihlášení
    });
});
