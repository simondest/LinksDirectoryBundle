<?php
namespace Vertacoo\LinksDirectoryBundle\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Vertacoo\LinksDirectoryBundle\Model\CategoryInterface;
/**
 * Doctrine Category Manager.
 *
 */
class CategoryManager 
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var ObjectRepository
     */
    protected $repository;
    /**
     * @var string
     */
    protected $class;


    /**
     * Constructor.
     *
     * @param ObjectManager $om
     * @param string        $class
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
     * @param Boolean           $andFlush Whether to flush the changes (default true)
     */
    public function updateCategory(CategoryInterface $category, $andFlush = true)
    {
        $this->objectManager->persist($category);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
    /**
     * {@inheritDoc}
     */
    public function deleteCategory(CategoryInterface $category)
    {
        $this->objectManager->remove($category);
        $this->objectManager->flush();
    }
    /**
     * {@inheritDoc}
     */
    public function findCategoryBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findCategoriesBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findBy($criteria, $orderBy);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoriesHierarchy(CategoryInterface $category = null, $directChildrenOnly = false, $includeCategory = false)
    {
        return $this->repository->childrenHierarchy($category, $directChildrenOnly, array(), $includeCategory);
    }

    /**
     * {@inheritDoc}
     */
    public function getCategoryPath(CategoryInterface $category)
    {
        return $this->repository->getPath($category);
    }
    
    /**
     * {@inheritdoc}
     */
    public function createCategory(CategoryInterface $parent = null)
    {
        $class = $this->getClass();
        $category = new $class();
        $category->setParent($parent);
    
        return $category;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getChildrenCategories(CategoryInterface $category = null, array $orderBy = null)
    {
        return $this->findCategoriesBy(array('parent' => $category), $orderBy);
    }
    
    public function getCategoriesWithLinks($category=null){
        $categories = array();
        $qb = $this->repository->getChildrenQueryBuilder();
        $qb->innerJoin($qb->getRootAlias().'.links', 'l');
        return  $qb->getQuery()->getResult();
        
        
        return $categories;
    }
    
    public function moveCategoryUp($category){
        $this->repository->moveUp($category);
    }
    public function moveCategoryDown($category){
        $this->repository->moveDown($category);
    }
    public function getRootCategories(){
        return $this->repository->getRootNodes();
       
    }
    
    public function getRepository(){
        return $this->repository;
    }
    public function getObjectManager(){
        return $this->objectManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getClass()
    {
        return $this->class;
    }
}

