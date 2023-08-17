import { TransportAndPaymentListItem } from './TransportAndPaymentListItem';
import { Radiobutton } from 'components/Forms/Radiobutton/Radiobutton';
import { TransportAndPaymentSelectItemLabel } from 'components/Pages/Order/TransportAndPayment/TransportAndPaymentSelect/TransportAndPaymentSelectItemLabel';
import { mapConnectionEdges } from 'helpers/mappers/connection';
import { useMemo } from 'react';
import { ListedStoreFragmentApi } from 'graphql/requests/stores/fragments/ListedStoreFragment.generated';
import { TransportWithAvailablePaymentsAndStoresFragmentApi } from 'graphql/requests/transports/fragments/TransportWithAvailablePaymentsAndStoresFragment.generated';

type StoreSelectProps = {
    selectedStoreUuid: string;
    transport: TransportWithAvailablePaymentsAndStoresFragmentApi;
    onSelectStoreCallback: (newStoreUuid: string | null) => void;
};

export const StoreSelect: FC<StoreSelectProps> = ({ selectedStoreUuid, transport, onSelectStoreCallback }) => {
    const mappedStores = useMemo(
        () => mapConnectionEdges<ListedStoreFragmentApi>(transport.stores?.edges),
        [transport.stores?.edges],
    );

    return (
        <ul>
            {mappedStores?.map((pickupPlace) => (
                <TransportAndPaymentListItem
                    key={pickupPlace.identifier}
                    isActive={selectedStoreUuid === pickupPlace.identifier}
                >
                    <Radiobutton
                        name="selectedStore"
                        id={pickupPlace.identifier}
                        value={pickupPlace.identifier}
                        checked={selectedStoreUuid === pickupPlace.identifier}
                        onChangeCallback={onSelectStoreCallback}
                        label={
                            <TransportAndPaymentSelectItemLabel
                                name={pickupPlace.name}
                                pickupPlaceDetail={pickupPlace}
                            />
                        }
                    />
                </TransportAndPaymentListItem>
            ))}
        </ul>
    );
};
