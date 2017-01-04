<?php

namespace Vertacoo\LinksDirectoryBundle\Model;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract Category implementation.
 *
 */
abstract class Category implements CategoryInterface
{
    protected $id;
    
    /**
     * @var string
     */
    protected $name; 
    
    /**
     * @var string
     */
    protected $slug;    
    
    protected $permalink;
    
    /**
     * @var CategoryInterface
     */
    protected $parent;     
    
    /**
     * @var integer
     */
    protected $position;      
    /**
     * @var integer
     */
    protected $rootNode;    
    
    /**
     * @var integer
     */
    protected $leftNode;
    /**
     * @var integer
     */
    protected $rightNode;    
    
    /**
     * @var integer
     */
    protected $levelDepth;    
    
    /**
     * @var Collection
     */
    protected $children;    
    /**
     * @var \Datetime
     */
    protected $createdAt;
    /**
     * @var \Datetime
     */
    protected $updatedAt;    
    
    protected $links;
    
    protected $indentedName;
    
    /**
     * Constructor.
     */      
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->links = new ArrayCollection();
    }    
    
    public function getId(){
        return $this->id;
    }
    
   
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }    
    
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * {@inheritdoc}
     */
    public function setParent(CategoryInterface $parent = null)
    {
        $this->parent = $parent;
    }
    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }    
    /**
     * {@inheritdoc}
     */
    public function getLevelDepth()
    {
        return $this->levelDepth;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setLevelDepth($levelDepth)
    {
        $this->levelDepth = $levelDepth;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getChildren()
    {   
        return $this->children;
    }
    public function getLinks()
    {   
        return $this->links;
    }
    
    /**
     * {@inheritdoc}
     */
    public function addChild(CategoryInterface $child)
    {
        if (!$this->hasChild($child)) {
            $child->setParent($this);
            $this->children->add($child);
        }   
    }
    public function addLink(LinkInterface $link)
    {
        if (!$this->hasLink($link)) {
            $link->setCategorie($this);
            $this->links->add($link);
        }   
    }
    
    /**
     * {@inheritdoc}
     */
    public function removeChild(CategoryInterface $child)
    {
        if ($this->children->removeElement($child)) {
            $child->setParent(null);
        }        
    }
    public function removeLink(LinkInterface $link)
    {
        if ($this->links->removeElement($link)) {
            $link->setParent(null);
        }        
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasChild(CategoryInterface $child)
    {
        return $this->children->contains($child);
    }
    public function hasLink(LinkInterface $link)
    {
        return $this->links->contains($link);
    }
    /**
     * {@inheritdoc}
     */   
    public function getCreatedAt()
    {
        return $this->createdAt;
    }    
      
    /**
     * {@inheritdoc}
     */   
    public function setCreatedAt(\Datetime $createdAt)
    {
        $this->createdAt = $createdAt;        
    }    
    
    /**
     * {@inheritdoc}
     */   
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    } 
  
    /**
     * {@inheritdoc}
     */   
    public function setUpdatedAt(\Datetime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;        
    }     
    
    /**
     * Returns the category name.
     * 
     * @return string
     */    
    public function __toString()
    {
        return (string) $this->getName();
    }       

    /**
     * Set rootNode
     *
     * @param integer $rootNode
     *
     * @return BaseCategory
     */
    public function setRootNode($rootNode)
    {
        $this->rootNode = $rootNode;

        return $this;
    }

    /**
     * Get rootNode
     *
     * @return integer
     */
    public function getRootNode()
    {
        return $this->rootNode;
    }

    /**
     * Set leftNode
     *
     * @param integer $leftNode
     *
     * @return BaseCategory
     */
    public function setLeftNode($leftNode)
    {
        $this->leftNode = $leftNode;

        return $this;
    }

    /**
     * Get leftNode
     *
     * @return integer
     */
    public function getLeftNode()
    {
        return $this->leftNode;
    }

    /**
     * Set rightNode
     *
     * @param integer $rightNode
     *
     * @return BaseCategory
     */
    public function setRightNode($rightNode)
    {
        $this->rightNode = $rightNode;

        return $this;
    }

    /**
     * Get rightNode
     *
     * @return integer
     */
    public function getRightNode()
    {
        return $this->rightNode;
    }
    /**
     * @return the $permalink
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param field_type $permalink
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
        return $this;
    }
    
    public function getIndentedName() {
        return str_repeat("---- > ", $this->levelDepth) . $this->name;
    }

}
