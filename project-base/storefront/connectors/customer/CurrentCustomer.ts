import { TypeDeliveryAddressFragment } from 'graphql/requests/customer/fragments/DeliveryAddressFragment.generated';
import { useCurrentCustomerUserQuery } from 'graphql/requests/customer/queries/CurrentCustomerUserQuery.generated';
import { CurrentCustomerType, DeliveryAddressType } from 'types/customer';

export const useCurrentCustomerData = (): CurrentCustomerType | null | undefined => {
    const [{ data }] = useCurrentCustomerUserQuery();

    if (!data?.currentCustomerUser) {
        return undefined;
    }

    const { currentCustomerUser } = data;
    const isCompanyCustomer = currentCustomerUser.__typename === 'CompanyCustomerUser';

    return {
        ...currentCustomerUser,
        companyCustomer: isCompanyCustomer,
        telephone: currentCustomerUser.telephone ? currentCustomerUser.telephone : '',
        companyName: isCompanyCustomer && currentCustomerUser.companyName ? currentCustomerUser.companyName : '',
        companyNumber: isCompanyCustomer && currentCustomerUser.companyNumber ? currentCustomerUser.companyNumber : '',
        companyTaxNumber:
            isCompanyCustomer && currentCustomerUser.companyTaxNumber ? currentCustomerUser.companyTaxNumber : '',
        defaultDeliveryAddress: currentCustomerUser.defaultDeliveryAddress
            ? mapDeliveryAddress(currentCustomerUser.defaultDeliveryAddress)
            : undefined,
        deliveryAddresses: mapDeliveryAddresses(currentCustomerUser.deliveryAddresses),
        passwordOld: '',
        passwordFirst: '',
        passwordSecond: '',
    };
};

const mapDeliveryAddress = (apiDeliveryAddressData: TypeDeliveryAddressFragment): DeliveryAddressType => {
    return {
        ...apiDeliveryAddressData,
        companyName: apiDeliveryAddressData.companyName ?? '',
        street: apiDeliveryAddressData.street ?? '',
        city: apiDeliveryAddressData.city ?? '',
        postcode: apiDeliveryAddressData.postcode ?? '',
        telephone: apiDeliveryAddressData.telephone ?? '',
        firstName: apiDeliveryAddressData.firstName ?? '',
        lastName: apiDeliveryAddressData.lastName ?? '',
        country: apiDeliveryAddressData.country?.name ?? '',
    };
};

const mapDeliveryAddresses = (apiDeliveryAddressesData: TypeDeliveryAddressFragment[]): DeliveryAddressType[] => {
    return apiDeliveryAddressesData.map((address) => mapDeliveryAddress(address));
};
