import dynamic from 'next/dynamic';
import { useSessionStore } from 'store/useSessionStore';
import { useDeferredRender } from 'utils/useDeferredRender';

const AutocompleteSearchPlaceholder = dynamic(() =>
    import('./AutocompleteSearchPlaceholder').then((component) => component.AutocompleteSearchPlaceholder),
);

const AutocompleteSearch = dynamic(
    () => import('./AutocompleteSearch').then((component) => component.AutocompleteSearch),
    {
        ssr: false,
        loading: () => <AutocompleteSearchPlaceholder />,
    },
);

export const DeferredAutocompleteSearch: FC = () => {
    const hadClientSideNavigation = useSessionStore((s) => s.hadClientSideNavigation);
    const shouldRender = useDeferredRender('autocomplete_search', hadClientSideNavigation);

    return (
        <div className="order-6 h-12 w-full transition lg:relative lg:order-4 lg:w-full vl:order-2 vl:flex-1">
            {shouldRender ? <AutocompleteSearch /> : <AutocompleteSearchPlaceholder />}
        </div>
    );
};
