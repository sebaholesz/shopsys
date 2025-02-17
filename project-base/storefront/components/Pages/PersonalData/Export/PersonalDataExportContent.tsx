import { usePersonalDataExportForm, usePersonalDataExportFormMeta } from './personalDataExportFormMeta';
import { UserText } from 'components/Basic/UserText/UserText';
import { SubmitButton } from 'components/Forms/Button/SubmitButton';
import { Form } from 'components/Forms/Form/Form';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { TextInputControlled } from 'components/Forms/TextInput/TextInputControlled';
import { SimpleLayout } from 'components/Layout/SimpleLayout/SimpleLayout';
import { usePersonalDataRequestMutation } from 'graphql/requests/personalData/mutations/PersonalDataRequestMutation.generated';
import { TypePersonalDataAccessRequestTypeEnum } from 'graphql/types';
import { GtmMessageOriginType } from 'gtm/enums/GtmMessageOriginType';
import useTranslation from 'next-translate/useTranslation';
import dynamic from 'next/dynamic';
import { useCallback } from 'react';
import { FormProvider, SubmitHandler } from 'react-hook-form';
import { PersonalDataExportFormType } from 'types/form';
import { blurInput } from 'utils/forms/blurInput';
import { clearForm } from 'utils/forms/clearForm';
import { handleFormErrors } from 'utils/forms/handleFormErrors';
import { useErrorPopupVisibility } from 'utils/forms/useErrorPopupVisibility';
import { showSuccessMessage } from 'utils/toasts/showSuccessMessage';

const ErrorPopup = dynamic(() => import('components/Forms/Lib/ErrorPopup').then((component) => component.ErrorPopup));

type PersonalDataExportContentProps = {
    contentSiteText: string | undefined;
};

export const PersonalDataExportContent: FC<PersonalDataExportContentProps> = ({ contentSiteText }) => {
    const { t } = useTranslation();
    const [, personalDataExport] = usePersonalDataRequestMutation();
    const [formProviderMethods] = usePersonalDataExportForm();
    const formMeta = usePersonalDataExportFormMeta(formProviderMethods);
    const [isErrorPopupVisible, setErrorPopupVisibility] = useErrorPopupVisibility(formProviderMethods);

    const onPersonalDataExportHandler = useCallback<SubmitHandler<PersonalDataExportFormType>>(
        async (data) => {
            blurInput();
            const personalDataExportResult = await personalDataExport({
                email: data.email,
                type: TypePersonalDataAccessRequestTypeEnum.Export,
            });

            if (personalDataExportResult.data?.RequestPersonalDataAccess) {
                showSuccessMessage(formMeta.messages.success);
            }

            handleFormErrors(personalDataExportResult.error, formProviderMethods, t, formMeta.messages.error);
            clearForm(personalDataExportResult.error, formProviderMethods, { email: '' });
        },
        [personalDataExport, formMeta.messages, formProviderMethods, t],
    );

    return (
        <>
            <SimpleLayout heading={t('Personal Data Export')}>
                {!!contentSiteText && (
                    <div className="mb-5 block text-justify">
                        <UserText htmlContent={contentSiteText} />
                    </div>
                )}
                <FormProvider {...formProviderMethods}>
                    <Form onSubmit={formProviderMethods.handleSubmit(onPersonalDataExportHandler)}>
                        <TextInputControlled
                            control={formProviderMethods.control}
                            formName={formMeta.formName}
                            name={formMeta.fields.email.name}
                            render={(textInput) => <FormLine>{textInput}</FormLine>}
                            textInputProps={{
                                label: formMeta.fields.email.label,
                                required: true,
                                type: 'email',
                                autoComplete: 'email',
                            }}
                        />
                        <div className="mt-8 flex w-full justify-center">
                            <SubmitButton>{t('Send')}</SubmitButton>
                        </div>
                    </Form>
                </FormProvider>
            </SimpleLayout>
            {isErrorPopupVisible && (
                <ErrorPopup
                    fields={formMeta.fields}
                    gtmMessageOrigin={GtmMessageOriginType.other}
                    onCloseCallback={() => setErrorPopupVisibility(false)}
                />
            )}
        </>
    );
};
