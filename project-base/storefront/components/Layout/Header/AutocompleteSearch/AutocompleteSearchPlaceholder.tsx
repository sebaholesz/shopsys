import useTranslation from 'next-translate/useTranslation';

export const AutocompleteSearchPlaceholder: FC = () => {
    const { t } = useTranslation();

    return (
        <div className="relative flex w-full transition-all">
            <div className="relative w-full">
                <input
                    autoComplete="off"
                    className="peer mb-0 h-12 w-full rounded border-2 border-white bg-white pr-20 pl-4 text-dark placeholder:text-grey placeholder:opacity-100 max-vl:border-primaryLight"
                    placeholder={t("Type what you're looking for")}
                    type="search"
                />

                <button
                    className="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer border-none"
                    title={t('Search')}
                    type="submit"
                />
            </div>
        </div>
    );
};
