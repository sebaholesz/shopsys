import { SkeletonModuleBreadcrumbs } from './SkeletonModuleBreadcrumbs';
import { SkeletonModuleProductListItem } from './SkeletonModuleProductListItem';
import { Webline } from 'components/Layout/Webline/Webline';
import Skeleton from 'react-loading-skeleton';
import { createEmptyArray } from 'utils/arrays/createEmptyArray';

export const SkeletonPageProductsList: FC = () => (
    <Webline>
        <SkeletonModuleBreadcrumbs count={2} />

        <div className="flex flex-row items-stretch gap-5">
            <Skeleton className="h-[1000px] w-[300px]" containerClassName="hidden vl:block" />

            <div className="w-full">
                <div className="mb-12 flex w-full flex-col gap-4 ">
                    <Skeleton className="h-9 w-5/6" />
                    <Skeleton className="mb-3 h-4" count={4} />
                </div>

                <div className="mb-7 flex flex-wrap gap-2">
                    {createEmptyArray(4).map((_, index) => (
                        <Skeleton
                            key={index}
                            className="h-full"
                            containerClassName="h-14 sm:w-[calc(50%-4px)] md:w-[calc(33.33%-8px)] lg:h-20 xl:w-[calc(25%-8px)] w-full"
                        />
                    ))}
                </div>

                <div className="flex">
                    <div className="flex w-full flex-col">
                        <div className="mb-7">
                            <Skeleton className="mb-2 h-6 w-40" />
                            <Skeleton className="h-10 rounded lg:w-20 " />
                        </div>

                        <div className="mt-10 mb-8 flex flex-wrap justify-between gap-2 vl:hidden">
                            <Skeleton className="h-12 w-40" />
                            <Skeleton className="h-12 w-32" />
                        </div>

                        <div className="mt-10 hidden items-center justify-between vl:flex">
                            <Skeleton className="h-9 w-24" containerClassName="flex gap-3" count={3} />
                            <Skeleton className="h-4 w-20" />
                        </div>

                        <div className="mb-7 grid w-full grid-cols-2 gap-3 lg:grid-cols-3 lg:gap-y-4 xl:grid-cols-4">
                            {createEmptyArray(9).map((_, index) => (
                                <SkeletonModuleProductListItem key={index} />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Webline>
);
