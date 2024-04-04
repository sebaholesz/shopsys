import { DeferredAutocompleteSearch } from './AutocompleteSearch/DeferredAutocompleteSearch';
import { DeferredCart } from './Cart/DeferredCart';
import { Logo } from './Logo/Logo';
import { DeferredMenuIconic } from './MenuIconic/DeferredMenuIconic';
import { DeferredMobileMenu } from './MobileMenu/DeferredMobileMenu';
import dynamic from 'next/dynamic';

const HeaderContact = dynamic(() => import('./Contact/HeaderContact').then((component) => component.HeaderContact));

type HeaderProps = {
    simpleHeader?: boolean;
};

export const Header: FC<HeaderProps> = ({ simpleHeader }) => {
    return (
        <div className="flex flex-wrap items-center gap-y-3 py-3 lg:gap-x-7 lg:pb-5 lg:pt-6">
            <Logo />

            {simpleHeader ? (
                <HeaderContact />
            ) : (
                <>
                    <DeferredAutocompleteSearch />
                    <DeferredMenuIconic />
                    <DeferredMobileMenu />
                    <DeferredCart />
                </>
            )}
        </div>
    );
};
