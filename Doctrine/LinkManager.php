<?php
namespace Vertacoo\LinksDirectoryBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Vertacoo\LinksDirectoryBundle\Model\LinkInterface;

/**
 * Doctrine Link Manager.
 */
class LinkManager
{

    /**
     *
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     *
     * @var ObjectRepository
     */
    protected $repository;

    /**
     *
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param ObjectManager $om            
     * @param string $class            
     */
    public function __construct(ObjectManager $om, $class)
    {
        $this->objectManager = $om;
        $this->repository = $om->getRepository($class);
        
        $metadata = $om->getClassMetadata($class);
        $this->class = $metadata->getName();
    }

    /**
     * Updates a link.
     *
     * @param LinkInterface $link            
     * @param Boolean $andFlush
     *            Whether to flush the changes (default true)
     */
    public function updateLink(LinkInterface $link, $andFlush = true)
    {
        $this->objectManager->persist($link);
        
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    public function deleteLink(LinkInterface $link)
    {
        $this->objectManager->remove($link);
        $this->objectManager->flush();
    }

    public function findLinkBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findLinksBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findBy($criteria, $orderBy);
    }

    public function getLinksQuery($orderBy = 'name', $direction = 'asc', $maxResult, $firstResult, $filter = null)
    {
        $qb = $this->objectManager->createQueryBuilder();
        
        $qb->select('link')->from($this->getClass(), 'link');
        if ($orderBy) {
            $qb->orderBy('link.' . $orderBy, $direction);
        }
        $qb->setMaxResults($maxResult);
        $qb->setFirstResult($firstResult);
        
        if ($filter['name']) {
            $qb->andWhere('link.name LIKE :filter_name');
            $qb->setParameter('filter_name','%'. $filter['name'] .'%');
        }
        if ($filter['categorie']) {
            $qb->andWhere('link.categorie = :filter_categorie');
            $qb->setParameter('filter_categorie',$filter['categorie']);
        }
        return $qb->getQuery();
    }

    public function createLink(LinkInterface $parent = null)
    {
        $class = $this->getClass();
        $link = new $class();
        
        return $link;
    }

    public function getClass()
    {
        return $this->class;
    }
}

