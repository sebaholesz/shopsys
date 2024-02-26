describe('Order repeat tests', () => {
    beforeEach(() => {
        cy.addProductToCartForTest().then((cart) => cy.storeCartUuidInLocalStorage(cart.uuid));
    });

    it('should repeat order (pre-fill cart) with initially empty cart', () => {
        // Opakování objednávky s původním prázdným košíkem
        // 1. přihlásit se jako no-reply@shopsys.com
        // 2. test počítá s tím, že je košík prázdný
        // 3. přejít přes horní menu “My account” do sekce “My orders”
        // 4. z přehledu objednávek kliknout na “repeat order” u objednávky
        // 5. ověřit, že došlo k přesměrování do košíku a naplnění košíku příslušnými produkty
    });

    it('should repeat order (pre-fill cart) with initially non-empty cart and allowed merging', () => {
        // Opakování objednávky, mergování košíku
        // 1. přihlásit se jako no-reply@shopsys.com
        // 2. přidat si produkt A do košíku z homepage
        // 3. přejít přes horní menu “My account” do sekce “My orders”
        // 4. z přehledu objednávek kliknout na “repeat order” u objednávky
        //     1. objednávka by měla obsahovat jiný/jiné produkty než produkt A
        // 5. ověřit zobrazení popupu s dotazem na mergování košíků
        // 6. zvolit možnost “yes”
        // 7. ověřit, že došlo k přesměrování do košíku a že obsahuje původní produkt A i produkty z opakované objednávky
    });

    it('should repeat order (pre-fill cart) with initially non-empty cart and disallowed merging', () => {
        // Opakování objednávky, zamezení mergování košíku
        // 1. přihlásit se jako no-reply@shopsys.com
        // 2. přidat si produkt A do košíku z homepage
        // 3. přejít přes horní menu “My account” do sekce “My orders”
        // 4. z přehledu objednávek kliknout na “repeat order” u objednávky
        //     1. objednávka by měla obsahovat jiný/jiné produkty než produkt A
        // 5. ověřit zobrazení popupu s dotazem na mergování košíků
        // 6. zvolit možnost “no”
        // 7. ověřit, že došlo k přesměrování do košíku a že košík obsahuje pouze produkt/y z opakované objednávky
    });
});
