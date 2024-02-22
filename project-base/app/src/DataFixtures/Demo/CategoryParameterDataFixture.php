<?php

declare(strict_types=1);

namespace App\DataFixtures\Demo;

use App\Model\Product\Parameter\ParameterRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use ReflectionClass;
use Shopsys\FrameworkBundle\Component\DataFixture\AbstractReferenceFixture;
use Shopsys\FrameworkBundle\Component\Domain\Domain;
use Shopsys\FrameworkBundle\Component\Translation\Translator;
use Shopsys\FrameworkBundle\Model\Category\CategoryParameterFacade;

class CategoryParameterDataFixture extends AbstractReferenceFixture implements DependentFixtureInterface
{
    /**
     * @param \Shopsys\FrameworkBundle\Model\Category\CategoryParameterFacade $categoryParameterFacade
     * @param \App\Model\Product\Parameter\ParameterRepository $parameterRepository
     * @param \Shopsys\FrameworkBundle\Component\Domain\Domain $domain
     */
    public function __construct(
        private CategoryParameterFacade $categoryParameterFacade,
        private ParameterRepository $parameterRepository,
        private Domain $domain,
    ) {
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        /** @var \App\Model\Category\Category $categoryElectronics */
        $categoryElectronics = $this->getReference(CategoryDataFixture::CATEGORY_ELECTRONICS);
        $firstDomainConfig = $this->domain->getDomainConfigById(Domain::FIRST_DOMAIN_ID);
        $firstDomainLocale = $firstDomainConfig->getLocale();
        $categoryDataFixtureClassReflection = new ReflectionClass(CategoryDataFixture::class);

        foreach ($categoryDataFixtureClassReflection->getConstants() as $constant) {
            /** @var \App\Model\Category\Category $category */
            $category = $this->getReference($constant);
            $parameters = $this->parameterRepository->getParametersUsedByProductsInCategory($category, $firstDomainConfig);
            $parametersId = [];

            foreach ($parameters as $parameter) {
                $parametersId[] = $parameter->getId();
            }
            $parametersCollapsed = [];

            if ($category === $categoryElectronics) {
                $parametersCollapsed = [
                    $this->getReference(ParameterDataFixture::PARAM_HDMI),
                    $this->getReference(ParameterDataFixture::PARAM_SCREEN_SIZE),
                ];
            }
            $this->categoryParameterFacade->saveRelation($category, $parametersId, $parametersCollapsed);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies(): array
    {
        return [
            CategoryDataFixture::class,
            ProductDataFixture::class,
        ];
    }
}
