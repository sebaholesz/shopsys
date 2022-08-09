<?php

namespace Shopsys\FrameworkBundle\Model\Product\Availability;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Shopsys\FrameworkBundle\Component\Grid\Grid;
use Shopsys\FrameworkBundle\Component\Grid\GridFactory;
use Shopsys\FrameworkBundle\Component\Grid\GridFactoryInterface;
use Shopsys\FrameworkBundle\Component\Grid\QueryBuilderDataSource;
use Shopsys\FrameworkBundle\Model\Localization\Localization;

class AvailabilityGridFactory implements GridFactoryInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Grid\GridFactory
     */
    protected $gridFactory;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Localization\Localization
     */
    protected $localization;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\FrameworkBundle\Component\Grid\GridFactory $gridFactory
     * @param \Shopsys\FrameworkBundle\Model\Localization\Localization $localization
     */
    public function __construct(
        EntityManagerInterface $em,
        GridFactory $gridFactory,
        Localization $localization
    ) {
        $this->em = $em;
        $this->gridFactory = $gridFactory;
        $this->localization = $localization;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Component\Grid\Grid
     */
    public function create(): Grid
    {
        $queryBuilder = $this->em->createQueryBuilder();
        $queryBuilder
            ->select('a, at')
            ->from(Availability::class, 'a')
            ->join('a.translations', 'at', Join::WITH, 'at.locale = :locale')
            ->setParameter('locale', $this->localization->getAdminLocale());
        $dataSource = new QueryBuilderDataSource($queryBuilder, 'a.id');

        $grid = $this->gridFactory->create('availabilityList', $dataSource);
        $grid->setDefaultOrder('dispatchTime');

        $grid->addColumn('name', 'at.name', t('Name'), true);
        $grid->addColumn('dispatchTime', 'a.dispatchTime', t('Number of days to despatch'), true);

        $grid->setActionColumnClassAttribute('table-col table-col-10');
        $grid->addDeleteActionColumn('admin_availability_deleteconfirm', ['id' => 'a.id'])
            ->setAjaxConfirm();

        $grid->setTheme('@ShopsysFramework/Admin/Content/Availability/listGrid.html.twig');

        return $grid;
    }
}
