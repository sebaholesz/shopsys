import { RegistrationDataInputApi } from '../../graphql/generated';
import { v4 as uuid } from 'uuid';

export const generateCustomerRegistrationData = (
    customerType: 'commonCustomer' | 'companyCustomer',
    staticEmail?: string,
) => {
    const generatedData: RegistrationDataInputApi = {
        firstName: 'John',
        lastName: 'Doe',
        email: staticEmail ?? generateEmail(),
        password: 'user123',
        telephone: '123456789',
        street: 'Uličnícká 123',
        city: 'Městečko',
        postcode: '12345',
        country: 'CZ',
        newsletterSubscription: true,
        productListsUuids: [],
        companyCustomer: false,
        cartUuid: null,
        companyName: null,
        companyNumber: null,
        companyTaxNumber: null,
        lastOrderUuid: null,
    };

    if (customerType === 'companyCustomer') {
        generatedData.companyName = 'Firma firemní';
        generatedData.companyNumber = '12345678';
        generatedData.companyTaxNumber = 'CZ12345678';
    }

    return generatedData;
};

const generateEmail = () => `no-reply-${uuid()}@shopsys.com.cz`;
