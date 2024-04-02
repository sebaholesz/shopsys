import { ArrowIcon } from 'components/Basic/Icon/ArrowIcon';
import React, { useState } from 'react';
import {
    Tab,
    TabList,
    TabListProps,
    TabPanel,
    TabPanelProps,
    TabProps,
    TabsProps,
    Tabs as TabsReact,
} from 'react-tabs';
import { twJoin } from 'tailwind-merge';

/**
 * In background of styled tab parts we are using - react-tabs components
 * https://github.com/reactjs/react-tabs
 */
type TabsContentProps = {
    headingTextMobile: string;
};

type TabFC<T = unknown> = FC<T> & { tabsRole: string };

// this is hack for react-tabs bug,
// when passing ...props to lib component, react-tabs are complaining about ref type
type PropsWithRef<T> = T & { ref: any };

export const Tabs: TabFC<Partial<TabsProps>> = ({ children, className, ...props }) => (
    <TabsReact className={twJoin('xl:my-auto xl:max-w-7xl', className)} {...props}>
        {children}
    </TabsReact>
);

export const TabsList: TabFC<Partial<TabListProps>> = ({ children }) => (
    <TabList className="z-above hidden flex-row border-b border-border lg:flex">{children}</TabList>
);

export const TabsListItem: TabFC<Partial<PropsWithRef<TabProps>>> = ({ children, className, ...props }) => (
    <Tab
        selectedClassName="isActive"
        className={twJoin(
            'relative bottom-0 cursor-pointer px-6 py-1 text-black no-underline before:absolute before:left-0 before:right-0 before:hidden before:bg-primary before:content-[""] hover:no-underline [&.isActive]:text-primary [&.isActive]:before:block',
            className,
        )}
        {...props}
    >
        {children}
    </Tab>
);

export const TabsContent: TabFC<TabsContentProps & Partial<PropsWithRef<TabPanelProps>>> = ({
    children,
    headingTextMobile,
    ...props
}) => {
    const [isActiveOnMobile, setIsActiveOnMobile] = useState<boolean | undefined>(false);
    const mobileTab = () => setIsActiveOnMobile(!isActiveOnMobile);

    return (
        <TabPanel
            forceRender
            className="flex flex-col flex-wrap lg:hidden [&.isActive]:flex [&.isActive]:lg:pt-12"
            selectedClassName="isActive"
            {...props}
        >
            <h3
                className="flex w-full cursor-pointer items-center justify-between rounded bg-blueLight py-4 px-5 font-bold lg:hidden"
                onClick={mobileTab}
            >
                {headingTextMobile}
                <ArrowIcon className={twJoin('w-4 rotate-0 transition', isActiveOnMobile && '-rotate-180 ')} />
            </h3>
            <div className={twJoin('w-full py-5', isActiveOnMobile ? 'block' : 'hidden lg:block')}>{children}</div>
        </TabPanel>
    );
};

// define element roles needed for react-tabs component
Tabs.tabsRole = 'Tabs';
TabsList.tabsRole = 'TabList';
TabsListItem.tabsRole = 'Tab';
TabsContent.tabsRole = 'TabPanel';
