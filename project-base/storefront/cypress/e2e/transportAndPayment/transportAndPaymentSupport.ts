import {
    OpeningHoursOfDayApi,
    TransportWithAvailablePaymentsAndStoresFragmentApi,
} from '../../../graphql/generated/index';
import { CyHttpMessages } from 'cypress/types/net-stubbing';
import { transport } from 'fixtures/demodata';
import { TIDs } from 'tids';

export const chooseTransportPersonalCollectionAndStore = (storeName: string) => {
    cy.getByTID([TIDs.pages_order_selectitem_label_name]).contains(transport.personalCollection.name).click();
    cy.getByTID([TIDs.layout_popup]);
    cy.getByTID([TIDs.pages_order_selectitem_label_name]).contains(storeName).click();
    cy.getByTID([TIDs.pages_order_pickupplace_popup_confirm]).scrollIntoView().click();
};

export const changeSelectionOfTransportByName = (transportName: string) => {
    cy.getByTID([TIDs.pages_order_transport, TIDs.pages_order_selectitem_label_name])
        .contains(transportName)
        .click('left');
};

export const changeSelectionOfPaymentByName = (paymentName: string) => {
    cy.getByTID([TIDs.pages_order_payment, TIDs.pages_order_selectitem_label_name]).contains(paymentName).click('left');
};

export const changeDayOfWeekInApiResponses = (dayOfWeek: number) => {
    cy.intercept('POST', '/graphql/', (req) => {
        req.reply((response) => {
            tryChangingDayOfWeekInTransportsApiResponse(response, dayOfWeek);
            tryChangingDayOfWeekInChangeTransportMutationApiResponse(response, dayOfWeek);
        });
    });
};

const tryChangingDayOfWeekInTransportsApiResponse = (
    response: CyHttpMessages.IncomingHttpResponse,
    dayOfWeek: number,
) => {
    response?.body?.data?.transports?.forEach((transport: TransportWithAvailablePaymentsAndStoresFragmentApi) => {
        transport?.stores?.edges?.forEach((edge) => {
            if (edge?.node?.openingHours) {
                edge.node.openingHours.isOpen = true;
                edge.node.openingHours.dayOfWeek = dayOfWeek;
                edge.node.openingHours.openingHoursOfDays = getStaticOpeningHoursOfDays();
            }
        });
    });
};

const tryChangingDayOfWeekInChangeTransportMutationApiResponse = (
    response: CyHttpMessages.IncomingHttpResponse,
    dayOfWeek: number,
) => {
    (
        response?.body?.data?.ChangeTransportInCart?.transport as TransportWithAvailablePaymentsAndStoresFragmentApi
    )?.stores?.edges?.forEach((edge) => {
        if (edge?.node?.openingHours) {
            edge.node.openingHours.isOpen = true;
            edge.node.openingHours.dayOfWeek = dayOfWeek;
            edge.node.openingHours.openingHoursOfDays = getStaticOpeningHoursOfDays();
        }
    });
};

const getStaticOpeningHoursOfDays = (): OpeningHoursOfDayApi[] => [
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-19T00:00:00+01:00',
        dayOfWeek: 1,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '06:00',
                closingTime: '11:00',
            },
            {
                __typename: 'OpeningHoursRange',
                openingTime: '13:00',
                closingTime: '15:00',
            },
        ],
    },
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-20T00:00:00+01:00',
        dayOfWeek: 2,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '07:00',
                closingTime: '11:00',
            },
            {
                __typename: 'OpeningHoursRange',
                openingTime: '13:00',
                closingTime: '15:00',
            },
        ],
    },
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-21T00:00:00+01:00',
        dayOfWeek: 3,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '08:00',
                closingTime: '11:00',
            },
            {
                __typename: 'OpeningHoursRange',
                openingTime: '13:00',
                closingTime: '15:00',
            },
        ],
    },
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-22T00:00:00+01:00',
        dayOfWeek: 4,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '09:00',
                closingTime: '11:00',
            },
            {
                __typename: 'OpeningHoursRange',
                openingTime: '13:00',
                closingTime: '15:00',
            },
        ],
    },
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-23T00:00:00+01:00',
        dayOfWeek: 5,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '10:00',
                closingTime: '11:00',
            },
            {
                __typename: 'OpeningHoursRange',
                openingTime: '13:00',
                closingTime: '15:00',
            },
        ],
    },
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-24T00:00:00+01:00',
        dayOfWeek: 6,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '08:00',
                closingTime: '11:00',
            },
        ],
    },
    {
        __typename: 'OpeningHoursOfDay',
        date: '2024-02-25T00:00:00+01:00',
        dayOfWeek: 7,
        openingHoursRanges: [
            {
                __typename: 'OpeningHoursRange',
                openingTime: '09:00',
                closingTime: '11:00',
            },
        ],
    },
];
