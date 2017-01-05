<?php

namespace Vertacoo\LinksDirectoryBundle\Model;
use Doctrine\Common\Collections\Collection;
/**
 * Category Interface.
 * 
 */
interface CategoryInterface
{   
       
    public function getId();
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName();    
    
    /**
     * Sets the name.
     *
     * @param string $name
     */
    public function setName($name);
    
    /**
     * Returns the slug.
     *
     * @return string
     */
    public function getSlug();    
    
    /**
     * Sets the slug.
     *
     * @param string $slug
     */
    public function setSlug($slug);    
    /**
     * Returns the permalink.
     * 
     * @return string
     */
    public function getPermalink();
    
    /**
     * Sets the permalink.
     * 
     * @param string $permalink
     */
    public function setPermalink($permalink);
    
    /**
     * Returns the parent.
     *
     * @return CategoryInterface|null
     */
    public function getParent();
    /**
     * Sets the parent.
     *
     * @param CategoryInterface|null $parent
     */
    public function setParent(CategoryInterface $parent = null);     
    
    /**
     * Returns the position.
     *
     * @return integer
     */
    public function getPosition();
    /**
     * Sets the position.
     *
     * @param integer $position
     */
    public function setPosition($position);
    /**
     * Returns the level depth.
     * 
     * @return integer
     */
    public function getLevelDepth();
    
    /**
     * Sets the level depth.
     * 
     * @param integer $levelDepth
     */
    public function setLevelDepth($levelDepth);
    
    /**
     * Returns all the chidren.
     *
     * @return Collection
     */
    public function getChildren(); 
    public function getLinks(); 
    
    /**
     * Adds a child.
     *
     * @param CategoryInterface $child
     */
    public function addChild(CategoryInterface $child);
    
    public function addLink(LinkInterface $link);
    
    /**
     * Removes a child.
     *
     * @param CategoryInterface $child
     */
    public function removeChild(CategoryInterface $child);    
    public function removeLink(LinkInterface $link);    
    
    /**
     * Checks whether category has given child.
     *
     * @param CategoryInterface $child
     *
     * @return Boolean
     */
    public function hasChild(CategoryInterface $child);
    public function hasLink(LinkInterface $link);
    
    /**
     * Returns the creation time.
     *
     * @return \Datetime
     */
    public function getCreatedAt(); 
    
    /**
     * Sets the creation time.
     * 
     * @param \Datetime $createdAt
     */
    public function setCreatedAt(\Datetime $createdAt);     
    
    /**
     * Returns the last update time.
     *
     * @return \Datetime
     */
    public function getUpdatedAt();    
    
    /**
     * Sets the last update time.
     * 
     * @param \Datetime|null $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null);  
    
}
