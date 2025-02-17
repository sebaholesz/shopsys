export const brandSencor = 'Sencor';

export const buttonName = {
    login: 'Login',
};

export const customer1 = {
    email: 'no-reply123@shopsys.com',
    emailRegistered: 'no-reply@shopsys.com',
    firstName: 'jméno',
    lastName: 'příjmení',
    phone: '777666555',
    billingStreet: 'fakturační ulice 1/15a',
    billingCity: 'fakturační město',
    billingPostCode: '70200',
    password: 'user123',
};

export const deliveryAddress = {
    firstName: 'Janek',
    lastName: 'Zpodgrunia',
    company: 'Jankostaw',
    phone: '162736482',
    street: 'Jankowice 153a',
    city: 'Jankowice',
    postCode: '73961',
    country: 'CZ',
};

export const link = {
    orderDetail: 'Track',
    myAccount: 'My account',
};

export const orderNote = 'Just a tiny note in the order.';

export const orderDetail = {
    numberHeading: 'Order number 1234567890',
    creationDate: '10/26/1999 10:10 AM',
};

export const payment = {
    creditCard: {
        uuid: '808f7a8a-6143-538e-a46d-3803519ecf00',
        name: 'Credit card',
    },
    cash: {
        uuid: '6e48952e-0f71-503c-8b58-f0ae9fc350c0',
        name: 'Cash',
    },
    onDelivery: {
        uuid: '2c3d2ede-bf1a-56bc-8c7d-44b7a672ef23',
        name: 'Cash on delivery',
    },
    payLater: {
        name: 'Pay later',
    },
};

export const placeholder = {
    password: 'Password',
    email: 'Your email',
    phone: 'Phone',
    firstName: 'First Name',
    lastName: 'Last Name',
    street: 'Street and house no.',
    city: 'City',
    postCode: 'Postcode',
    coupone: 'Coupon',
    note: 'Note',
    company: 'Company',
};

export const products = {
    helloKitty: {
        uuid: '55bb22ab-bb88-5459-a464-005b948d8c78',
        name: '22" Sencor SLE 22F46DM4 HELLO KITTY',
        fullName: 'Television 22" Sencor SLE 22F46DM4 HELLO KITTY plasma',
        catnum: 9177759,
        url: '/television-22-sencor-sle-22f46dm4-hello-kitty-plasma',
    },
    philips32PFL4308: {
        uuid: '7de699f8-bc41-5642-9ad8-3924a9d49f47',
        url: '/philips-32pfl4308',
    },
    philips54CRT: {
        name: '54" Philips CRT 32PFL4308',
        catnum: 9176588,
    },
} as const;

export const quantityUnit = 'pc';

export const transport = {
    personalCollection: {
        uuid: 'b7461a55-b6e6-5b4f-a3c9-92f35366fe41',
        name: 'Personal collection',
        storeOstrava: {
            uuid: '67ac2c38-7bdd-59fa-b762-0704cee8323e',
            name: 'Ostrava',
        },
    },
    czechPost: {
        uuid: 'f411f93c-6658-5bf6-ad68-cd69a83a88c0',
        name: 'Czech post',
    },
    ppl: {
        uuid: '5d4619f7-a98f-5bff-b296-2a0887430a63',
        name: 'PPL',
    },
    droneDelivery: {
        name: 'Drone delivery',
    },
};

export const url = {
    cart: '/cart',
    search: '/search?q=',
    brandsOverwiev: '/brands-overview',
    order: {
        transportAndPayment: '/order/transport-and-payment',
        contactInformation: '/order/contact-information',
        orderConfirmation: '/order-confirmation',
        orderDetail: '/order-detail/',
    },
    login: '/login',
    loginWithCustomerRedirect: '/login?r=customer',
    customer: '/customer',
    categoryElectronics: '/electronics',
} as const;

export const DEFAULT_APP_STORE = {
    state: {
        loginLoading: null,
        cartUuid: null as string | null,
        comparisonUuid: null,
        contactInformation: {
            email: '',
            telephone: '',
            firstName: '',
            lastName: '',
            street: '',
            city: '',
            postcode: '',
            country: { value: '', label: '' },
            companyName: '',
            companyNumber: '',
            companyTaxNumber: '',
            differentDeliveryAddress: false,
            deliveryFirstName: '',
            deliveryLastName: '',
            deliveryCompanyName: '',
            deliveryTelephone: '',
            deliveryStreet: '',
            deliveryCity: '',
            deliveryPostcode: '',
            deliveryCountry: { value: '', label: '' },
            deliveryAddressUuid: '',
            newsletterSubscription: false,
            note: '',
        },
        packeteryPickupPoint: null,
        userConsent: {
            statistics: false,
            marketing: false,
            preferences: false,
        },
        wishlistUuid: null,
    },
    version: 0,
};
