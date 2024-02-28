<?php

return [
    Craue\FormFlowBundle\CraueFormFlowBundle::class => ['all' => true],
    Doctrine\Bundle\DoctrineBundle\DoctrineBundle::class => ['all' => true],
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['all' => true],
    Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle::class => ['all' => true],
    FM\ElfinderBundle\FMElfinderBundle::class => ['all' => true],
    Knp\Bundle\MenuBundle\KnpMenuBundle::class => ['all' => true],
    Overblog\GraphQLBundle\OverblogGraphQLBundle::class => ['all' => true],
    Overblog\GraphiQLBundle\OverblogGraphiQLBundle::class => ['dev' => true],
    Presta\SitemapBundle\PrestaSitemapBundle::class => ['all' => true],
    Prezent\Doctrine\TranslatableBundle\PrezentDoctrineTranslatableBundle::class => ['all' => true],
    Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle::class => ['all' => true],
    Shopsys\FormTypesBundle\ShopsysFormTypesBundle::class => ['all' => true],
    Shopsys\FrontendApiBundle\ShopsysFrontendApiBundle::class => ['all' => true],
    Shopsys\MigrationBundle\ShopsysMigrationBundle::class => ['all' => true],
    Shopsys\ProductFeed\HeurekaBundle\ShopsysProductFeedHeurekaBundle::class => ['all' => true],
    Shopsys\ProductFeed\GoogleBundle\ShopsysProductFeedGoogleBundle::class => ['all' => true],
    Shopsys\CategoryFeed\LuigisBoxBundle\ShopsysCategoryFeedLuigisBoxBundle::class => ['all' => true],
    Shopsys\ProductFeed\ZboziBundle\ShopsysProductFeedZboziBundle::class => ['all' => true],
    Shopsys\ProductFeed\HeurekaDeliveryBundle\ShopsysProductFeedHeurekaDeliveryBundle::class => ['all' => true],
    Shopsys\ProductFeed\LuigisBoxBundle\ShopsysProductFeedLuigisBoxBundle::class => ['all' => true],
    Shopsys\ArticleFeed\LuigisBoxBundle\ShopsysArticleFeedLuigisBoxBundle::class => ['all' => true],
    Shopsys\LuigisBoxBundle\ShopsysLuigisBoxBundle::class => ['all' => true],
    Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle::class => ['all' => true],
    Snc\RedisBundle\SncRedisBundle::class => ['all' => true],
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    # JMS TranslationBundle needs to be after Symfony Framework as Symfony Framework also adds translate commands
    JMS\TranslationBundle\JMSTranslationBundle::class => ['all' => true],
    JMS\SerializerBundle\JMSSerializerBundle::class => ['all' => true],
    Symfony\Bundle\MonologBundle\MonologBundle::class => ['all' => true],
    Symfony\Bundle\SecurityBundle\SecurityBundle::class => ['all' => true],
    Symfony\Bundle\TwigBundle\TwigBundle::class => ['all' => true],
    Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle::class => ['all' => true],
    FOS\CKEditorBundle\FOSCKEditorBundle::class => ['all' => true],
    Fp\JsFormValidatorBundle\FpJsFormValidatorBundle::class => ['all' => true],
    Shopsys\FrameworkBundle\ShopsysFrameworkBundle::class => ['all' => true],
    Symfony\Bundle\WebProfilerBundle\WebProfilerBundle::class => ['dev' => true],
    Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true],
    Symfony\WebpackEncoreBundle\WebpackEncoreBundle::class => ['all' => true],
    Shopsys\S3Bridge\ShopsysS3BridgeBundle::class => ['all' => true],
    Scheb\TwoFactorBundle\SchebTwoFactorBundle::class => ['all' => true],
    Sentry\SentryBundle\SentryBundle::class => ['prod' => true],
    Overblog\DataLoaderBundle\OverblogDataLoaderBundle::class => ['all' => true],
];
