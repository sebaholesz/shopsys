<?php

declare(strict_types=1);

namespace Tests\FrontendApiBundle\Functional\Settings;

use App\DataFixtures\Demo\BlogArticleDataFixture;
use Shopsys\FrameworkBundle\Model\Blog\Category\BlogCategory;
use Tests\FrontendApiBundle\Test\GraphQlTestCase;

class BlogUrlSettingsTest extends GraphQlTestCase
{
    public function testGetBlogUrlSettings(): void
    {
        $graphQlType = 'settings';
        $response = $this->getResponseContentForGql(__DIR__ . '/graphql/BlogUrlSettingsQuery.graphql');
        $responseData = $this->getResponseDataForGraphQlType($response, $graphQlType);
        $expectedBlogUrl = $this->getLocalizedPathOnFirstDomainByRouteName(
            'front_blogcategory_detail',
            [
                'id' => $this->getReference(BlogArticleDataFixture::FIRST_DEMO_BLOG_CATEGORY, BlogCategory::class)->getId(),
            ],
        );

        $expectedData = ['blogUrl' => $expectedBlogUrl];

        self::assertSame($expectedData, $responseData);
    }
}
