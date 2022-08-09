<?php

namespace Shopsys\FrameworkBundle\Component\Router\FriendlyUrl;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\Exception\FriendlyUrlNotFoundException;

class FriendlyUrlRepository
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
     * @return \Doctrine\ORM\EntityRepository<\Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl>
     */
    protected function getFriendlyUrlRepository(): EntityRepository
    {
        return $this->em->getRepository(FriendlyUrl::class);
    }

    /**
     * @param int $domainId
     * @param string $slug
     * @return \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl|null
     */
    public function findByDomainIdAndSlug(int $domainId, string $slug): ?FriendlyUrl
    {
        return $this->getFriendlyUrlRepository()->findOneBy(
            [
                'domainId' => $domainId,
                'slug' => $slug,
            ]
        );
    }

    /**
     * @param int $domainId
     * @param string $routeName
     * @param int $entityId
     * @return \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl
     */
    public function getMainFriendlyUrl(int $domainId, string $routeName, int $entityId): FriendlyUrl
    {
        $criteria = [
            'domainId' => $domainId,
            'routeName' => $routeName,
            'entityId' => $entityId,
            'main' => true,
        ];
        $friendlyUrl = $this->getFriendlyUrlRepository()->findOneBy($criteria);

        if ($friendlyUrl === null) {
            throw new FriendlyUrlNotFoundException();
        }

        return $friendlyUrl;
    }

    /**
     * @param int $domainId
     * @param string $routeName
     * @param int $entityId
     * @return \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl|null
     */
    public function findMainFriendlyUrl(int $domainId, string $routeName, int $entityId): ?FriendlyUrl
    {
        $criteria = [
            'domainId' => $domainId,
            'routeName' => $routeName,
            'entityId' => $entityId,
            'main' => true,
        ];

        return $this->getFriendlyUrlRepository()->findOneBy($criteria);
    }

    /**
     * @param string $routeName
     * @param int $entityId
     * @return \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl[]
     */
    public function getAllByRouteNameAndEntityId(string $routeName, int $entityId): array
    {
        $criteria = [
            'routeName' => $routeName,
            'entityId' => $entityId,
        ];

        return $this->getFriendlyUrlRepository()->findBy(
            $criteria,
            [
                'domainId' => 'ASC',
                'slug' => 'ASC',
            ]
        );
    }

    /**
     * @param string $routeName
     * @param int $entityId
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl[]
     */
    public function getAllByRouteNameAndEntityIdAndDomainId(string $routeName, int $entityId, int $domainId): array
    {
        $criteria = [
            'routeName' => $routeName,
            'entityId' => $entityId,
            'domainId' => $domainId,
        ];

        return $this->getFriendlyUrlRepository()->findBy($criteria);
    }

    /**
     * @param object[]|int[] $entitiesOrEntityIds
     * @param string $routeName
     * @param int $domainId
     * @return \Shopsys\FrameworkBundle\Component\Router\FriendlyUrl\FriendlyUrl[]
     */
    public function getMainFriendlyUrlsByEntitiesIndexedByEntityId(array $entitiesOrEntityIds, string $routeName, int $domainId): array
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('f')
            ->from(FriendlyUrl::class, 'f', 'f.entityId')
            ->andWhere('f.routeName = :routeName')->setParameter('routeName', $routeName)
            ->andWhere('f.entityId IN (:entities)')->setParameter('entities', $entitiesOrEntityIds)
            ->andWhere('f.domainId = :domainId')->setParameter('domainId', $domainId)
            ->andWhere('f.main = TRUE');

        return $queryBuilder->getQuery()->execute();
    }
}
