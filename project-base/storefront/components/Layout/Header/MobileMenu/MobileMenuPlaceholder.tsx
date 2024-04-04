import { MenuIcon } from 'components/Basic/Icon/MenuIcon';
import useTranslation from 'next-translate/useTranslation';
import { twJoin } from 'tailwind-merge';

export const MobileMenuPlaceholder: FC = () => {
    const { t } = useTranslation();

    return (
        <div className={twJoin('flex h-10 w-full cursor-pointer items-center rounded bg-orangeLight p-3')}>
            <div className="flex w-4 items-center justify-center">
                <MenuIcon className="w-4" />
            </div>

            <span className="ml-1 w-7 text-xs">{t('Menu')}</span>
        </div>
    );
};
