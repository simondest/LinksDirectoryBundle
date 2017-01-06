<?php
namespace Vertacoo\LinksDirectoryBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Vertacoo\LinksDirectoryBundle\Model\CategoryInterface;

/**
 * Doctrine Category Manager.
 */
class CategoryManager
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
     * Updates a category.
     *
     * @param CategoryInterface $category            
     * @param Boolean $andFlush
     *            Whether to flush the changes (default true)
     */
    public function updateCategory(CategoryInterface $category, $andFlush = true)
    {
        $this->objectManager->persist($category);
        
        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function deleteCategory(CategoryInterface $category)
    {
        $this->objectManager->remove($category);
        $this->objectManager->flush();
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function findCategoryBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function findCategoriesBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getCategoriesHierarchy(CategoryInterface $category = null, $directChildrenOnly = false, $includeCategory = false)
    {
        return $this->repository->childrenHierarchy($category, $directChildrenOnly, array(), $includeCategory);
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getCategoryPath(CategoryInterface $category)
    {
        return $this->repository->getPath($category);
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function createCategory(CategoryInterface $parent = null)
    {
        $class = $this->getClass();
        $category = new $class();
        $category->setParent($parent);
        
        return $category;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getChildrenCategories(CategoryInterface $category = null, array $orderBy = null, $direction = null, $maxResult = null, $firstResult = null)
    {
        return $this->getChildrenCategoriesQuery($category, $orderBy, $direction, $maxResult, $firstResult)->getResult();
        
        return $this->findCategoriesBy(array(
            'parent' => $category
        ), $orderBy, $maxResult, $firstResult);
    }

    public function getChildrenCategoriesQuery(CategoryInterface $category = null, array $orderBy = null, $direction = null, $maxResult = null, $firstResult = null)
    {
        $qb = $this->repository->getChildrenQueryBuilder($category, true, $orderBy, $direction);
        $qb->setFirstResult($firstResult)
            ->setMaxResults($maxResult);
        return $qb->getQuery();
    }

    public function getCategoriesWithLinksQuery($category = null)
    {
        $qb = $this->objectManager->createQuery('SELECT node, l FROM ' . $this->class . ' node INNER JOIN node.links l ORDER BY node.leftNode ASC');
        return $qb;
    }

    public function moveCategoryUp($category)
    {
        $this->repository->moveUp($category);
    }

    public function moveCategoryDown($category)
    {
        $this->repository->moveDown($category);
    }

    public function getRootCategories()
    {
        return $this->repository->getRootNodes();
    }

    public function getRepository()
    {
        return $this->repository;
    }

    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     *
     * {@inheritdoc}
     *
     */
    public function getClass()
    {
        return $this->class;
    }
}

