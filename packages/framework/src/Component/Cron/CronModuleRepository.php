<?php

namespace Shopsys\FrameworkBundle\Component\Cron;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CronModuleRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var \Shopsys\FrameworkBundle\Component\Cron\CronModuleFactoryInterface
     */
    protected $cronModuleFactory;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     * @param \Shopsys\FrameworkBundle\Component\Cron\CronModuleFactoryInterface $cronModuleFactory
     */
    public function __construct(EntityManagerInterface $em, CronModuleFactoryInterface $cronModuleFactory)
    {
        $this->em = $em;
        $this->cronModuleFactory = $cronModuleFactory;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository<\Shopsys\FrameworkBundle\Component\Cron\CronModule>
     */
    protected function getCronModuleRepository(): EntityRepository
    {
        return $this->em->getRepository(CronModule::class);
    }

    /**
     * @param string $serviceId
     * @return \Shopsys\FrameworkBundle\Component\Cron\CronModule
     */
    public function getCronModuleByServiceId(string $serviceId): CronModule
    {
        $cronModule = $this->getCronModuleRepository()->find($serviceId);
        if ($cronModule === null) {
            $cronModule = $this->cronModuleFactory->create($serviceId);
            $this->em->persist($cronModule);
            $this->em->flush();
        }

        return $cronModule;
    }

    /**
     * @return string[]
     */
    public function getAllScheduledCronModuleServiceIds(): array
    {
        $query = $this->em->createQuery(
            'SELECT cm.serviceId FROM ' . CronModule::class . ' cm WHERE cm.scheduled = TRUE'
        );

        return $query->getSingleColumnResult();
    }

    /**
     * @return \Shopsys\FrameworkBundle\Component\Cron\CronModule[]
     */
    public function findAllIndexedByServiceId(): array
    {
        return $this->getCronModuleRepository()->createQueryBuilder('cm')
            ->indexBy('cm', 'cm.serviceId')
            ->getQuery()->getResult();
    }
}
