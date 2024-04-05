import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import { useNavigationQuery } from 'graphql/requests/navigation/queries/NavigationQuery.generated';
import { twJoin } from 'tailwind-merge';

export const NavigationPlaceholder: FC = () => {
    const [{ data: navigationData }] = useNavigationQuery();

    if (!navigationData?.navigation.length) {
        return null;
    }

    return (
        <ul className="relative hidden w-full lg:flex lg:gap-6 xl:gap-12">
            {navigationData.navigation.map((navigationItem, index) => {
                const hasChildren = !!navigationItem.categoriesByColumns.length;
                const isCatalogLink = navigationItem.link === `/#`;
                return (
                    <li key={index} className="group">
                        <ExtendedNextLink
                            href={navigationItem.link}
                            type={isCatalogLink ? 'homepage' : 'category'}
                            className={twJoin(
                                'relative m-0 flex items-center px-2 py-4 text-sm font-bold uppercase text-white no-underline hover:text-orangeLight hover:no-underline group-hover:text-orangeLight group-hover:no-underline vl:text-base',
                            )}
                        >
                            {navigationItem.name}
                            {hasChildren && (
                                <ArrowIcon className="ml-2 text-white group-hover:rotate-180 group-hover:text-orangeLight" />
                            )}
                        </ExtendedNextLink>
                    </li>
                );
            })}
        </ul>
    );
};
