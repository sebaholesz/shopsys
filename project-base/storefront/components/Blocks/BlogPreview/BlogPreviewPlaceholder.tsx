import { ExtendedNextLink } from 'components/Basic/ExtendedNextLink/ExtendedNextLink';
import { BLOG_PREVIEW_VARIABLES } from 'config/constants';
import { useBlogArticlesQuery } from 'graphql/requests/articlesInterface/blogArticles/queries/BlogArticlesQuery.generated';
import useTranslation from 'next-translate/useTranslation';

export const BlogPreviewPlaceholder: FC = () => {
    const { t } = useTranslation();
    const [{ data: blogPreviewData }] = useBlogArticlesQuery({
        variables: BLOG_PREVIEW_VARIABLES,
    });
    const blogUrl: string | undefined = './blog';

    return (
        <div>
            <h2 className=" text-creamWhite">{t('Shopsys magazine')}</h2>

            {!!blogUrl && (
                <ExtendedNextLink className=" text-creamWhite " href={blogUrl} type="blogCategory">
                    {t('View all')}
                </ExtendedNextLink>
            )}

            {blogPreviewData?.blogArticles.edges?.slice(0, 2).map(
                (item) =>
                    item?.node && (
                        <div key={item.node.uuid}>
                            {item.node.blogCategories.map(
                                (blogCategory) =>
                                    !!blogCategory.parent && (
                                        <ExtendedNextLink
                                            key={blogCategory.uuid}
                                            href={blogCategory.link}
                                            type="blogCategory"
                                        >
                                            {blogCategory.name}
                                        </ExtendedNextLink>
                                    ),
                            )}

                            <ExtendedNextLink className="text-white" href={item.node.link} type="blogArticle">
                                {item.node.name}
                            </ExtendedNextLink>

                            <div>{item.node.perex}</div>
                        </div>
                    ),
            )}

            {blogPreviewData?.blogArticles.edges?.slice(2).map(
                (item) =>
                    item?.node && (
                        <div key={item.node.uuid}>
                            {item.node.blogCategories.map(
                                (blogCategory) =>
                                    !!blogCategory.parent && (
                                        <ExtendedNextLink
                                            key={blogCategory.uuid}
                                            href={blogCategory.link}
                                            type="blogCategory"
                                        >
                                            {blogCategory.name}
                                        </ExtendedNextLink>
                                    ),
                            )}

                            <ExtendedNextLink className="text-white" href={item.node.link} type="blogArticle">
                                {item.node.name}
                            </ExtendedNextLink>

                            <div>{item.node.perex}</div>
                        </div>
                    ),
            )}
        </div>
    );
};
