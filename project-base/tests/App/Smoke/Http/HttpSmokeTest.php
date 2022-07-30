<?php

declare(strict_types=1);

namespace Tests\App\Smoke\Http;

use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\HttpSmokeTesting\HttpSmokeTestCase;
use Shopsys\HttpSmokeTesting\RouteConfigCustomizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HttpSmokeTest extends HttpSmokeTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        static::$container->get(Domain::class)
            ->switchDomainById(Domain::FIRST_DOMAIN_ID);
    }

    /**
     * @param \Shopsys\HttpSmokeTesting\RouteConfigCustomizer $routeConfigCustomizer
     */
    protected function customizeRouteConfigs(RouteConfigCustomizer $routeConfigCustomizer): void
    {
        $routeConfigCustomization = new RouteConfigCustomization(static::$container);
        $routeConfigCustomization->customizeRouteConfigs($routeConfigCustomizer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function handleRequest(Request $request): Response
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = static::$container->get('doctrine.orm.entity_manager');

        $entityManager->beginTransaction();
        ob_start();
        $response = parent::handleRequest($request);
        ob_end_clean();
        $entityManager->rollback();

        return $response;
    }
}
