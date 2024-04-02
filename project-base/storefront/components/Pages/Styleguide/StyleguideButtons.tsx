import { StyleguideSection, StyleguideSubSection } from './StyleguideElements';
import { Button } from 'components/Forms/Button/Button';

export const StyleguideButtons: FC = () => {
    return (
        <StyleguideSection className="flex flex-col gap-5" title="Buttons">
            <StyleguideSubSection className="flex flex-wrap items-center gap-3" title="Variants">
                <Button variant="primary">Primary</Button>
                <Button variant="secondary">Secondary</Button>
            </StyleguideSubSection>

            <StyleguideSubSection className="flex flex-wrap items-center gap-3" title="Sizes">
                <Button>Default</Button>
                <Button size="small">Small</Button>
            </StyleguideSubSection>
        </StyleguideSection>
    );
};
