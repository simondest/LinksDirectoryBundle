<?php
namespace Vertacoo\LinksDirectoryBundle\Model;

interface LinkInterface
{
   
    public function getId();
    
    /**
     * @return the $name
     */
    public function getName();
   
    
    /**
     * @return the $description
     */
    public function getDescription();
    
    /**
     * @return the $url
     */
    public function getUrl();
    
    
    /**
     * @return the $imageName
     */
    public function getImageName();
    
    
    /**
     * @return the $updatedAt
     */
    public function getUpdatedAt();
    
    
    /**
     * @return the $createdAt
     */
    public function getCreatedAt();
    
    public function getCategorie();
    
    /**
     * @param field_type $name
     */
    public function setName($name);
    
    
    /**
     * @param field_type $description
     */
    public function setDescription($description);
    
    
    /**
     * @param field_type $url
     */
    public function setUrl($url);
    
    
    /**
     * @param field_type $imageName
     */
    public function setImageName($imageName);
    
    /**
     * @param field_type $updatedAt
     */
    public function setUpdatedAt($updatedAt);
    
    
    /**
     * @param field_type $createdAt
     */
    public function setCreatedAt($createdAt);
    
    public function setCategorie($categorie);
}

