<?php
namespace Vertacoo\LinksDirectoryBundle\Model;

use Vertacoo\LinksDirectoryBundle\Model\LinkInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * 
 * @author simon
 * @Vich\Uploadable
 *
 */
abstract class Link implements LinkInterface
{

    protected $id;

    protected $name;

    protected $description;

    protected $url;

    /**
     * @Vich\UploadableField(mapping="link_image", fileNameProperty="image_name")
     * 
     * @var File
     */
    private $imageFile;

    protected $imageName;

    protected $updatedAt;

    protected $createdAt;

    protected $categorie;

    public function __toString()
    {
        return (string) $this->getName() ? $this->getName() : 'n/a';
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return the $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return the $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @return the $url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     *
     * @return the $imageName
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     *
     * @return the $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     *
     * @return the $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     *
     * @param field_type $name            
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     *
     * @param field_type $description            
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     *
     * @param field_type $url            
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     *
     * @param field_type $imageName            
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     *
     * @param field_type $updatedAt            
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     *
     * @param field_type $createdAt            
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     *
     * @return the $categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     *
     * @param field_type $categorie            
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }
    
    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
    
        if ($image) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    
        return $this;
    }
    
    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }
}
