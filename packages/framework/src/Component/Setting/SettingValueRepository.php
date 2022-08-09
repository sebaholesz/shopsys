<?php

namespace Shopsys\FrameworkBundle\Component\Setting;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class SettingValueRepository
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository<\Shopsys\FrameworkBundle\Component\Setting\SettingValue>
     */
    protected function getSettingValueRepository(): EntityRepository
    {
        return $this->em->getRepository(SettingValue::class);
    }

    /**
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Component\Setting\SettingValue[]
     */
    public function getAllByDomainId(int $domainId): array
    {
        return $this->getSettingValueRepository()->findBy(['domainId' => $domainId]);
    }

    /**
     * @param int $fromDomainId
     * @param int $toDomainId
     */
    public function copyAllMultidomainSettings(int $fromDomainId, int $toDomainId): void
    {
        $this->em->getConnection()->executeStatement(
            'INSERT INTO setting_values (name, value, type, domain_id)
            SELECT name, value, type, :toDomainId
            FROM setting_values
            WHERE domain_id = :fromDomainId
                AND EXISTS (
                    SELECT 1
                    FROM setting_values
                    WHERE domain_id IS NOT NULL
                        AND domain_id != :commonDomainId
                )',
            [
                'toDomainId' => $toDomainId,
                'fromDomainId' => $fromDomainId,
                'commonDomainId' => SettingValue::DOMAIN_ID_COMMON,
            ],
            [
                'toDomainId' => Types::INTEGER,
                'fromDomainId' => Types::INTEGER,
                'commonDomainId' => Types::INTEGER,
            ]
        );
    }
}
