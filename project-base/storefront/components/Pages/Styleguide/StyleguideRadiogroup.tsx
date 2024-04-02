import { StyleguideSection } from './StyleguideElements';
import { yupResolver } from '@hookform/resolvers/yup';
import { FormLine } from 'components/Forms/Lib/FormLine';
import { RadiobuttonGroup } from 'components/Forms/Radiobutton/RadiobuttonGroup';
import { useShopsysForm } from 'hooks/forms/useShopsysForm';
import { FormProvider } from 'react-hook-form';
import * as Yup from 'yup';

const getStyleguideExampleFormResolver = () =>
    yupResolver(
        Yup.object().shape<Record<keyof { country: 'cz' | 'de' }, any>>({
            country: Yup.string().oneOf(['cz', 'de']),
        }),
    );

export const StyleguideRadiogroup: FC = () => {
    const formProviderMethods = useShopsysForm(getStyleguideExampleFormResolver(), {
        country: 'cz',
    });

    const formMeta = {
        formName: 'contact-information-form',
        messages: {
            error: 'Could not create order',
        },
        fields: {
            country: {
                name: 'country' as const,
                label: 'Country',
            },
        },
    };

    return (
        <StyleguideSection className="flex flex-col gap-3" title="RadioGroup">
            <FormProvider {...formProviderMethods}>
                <RadiobuttonGroup
                    control={formProviderMethods.control}
                    formName={formMeta.formName}
                    name={formMeta.fields.country.name}
                    radiobuttons={[
                        {
                            label: 'Czechia',
                            value: 'cz',
                        },
                        {
                            label: 'Germany',
                            value: 'de',
                        },
                    ]}
                    render={(radiobutton, key) => (
                        <FormLine key={key} bottomGap className="w-full flex-none lg:w-1/2">
                            {radiobutton}
                        </FormLine>
                    )}
                />
            </FormProvider>
        </StyleguideSection>
    );
};
