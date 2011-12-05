<?php

namespace ImiBorbas\VideoGalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImiBorbas\VideoGalleryBundle\Entity\Video
 *
 * @ORM\Table(name="video")
 * @ORM\Entity(repositoryClass="ImiBorbas\VideoGalleryBundle\Entity\VideoRepository")
 */
class Video
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $videoId
     *
     * @ORM\Column(name="video_id", type="string", length=255)
     */
    private $videoId;
    
    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $thumbnailUrl
     *
     * @ORM\Column(name="thumbnail_url", type="string", length=255)
     */
    private $thumbnailUrl;

    /**
     * @var integer $position
     *
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

    /**
     * @var string $embedCode
     *
     * @ORM\Column(name="embed_code", type="text")
     */
    private $embedCode;
    
    /**
     * @var VideoGallery $videoGallery
     *
     * @ORM\ManyToOne(targetEntity="VideoGallery", inversedBy="videos")
     * @ORM\JoinColumn(name="video_gallery_id", referencedColumnName="id")
     */
    private $videoGallery;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set videoId
     *
     * @param string $videoId
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * Get videoId
     *
     * @return string 
     */
    public function getVideoId()
    {
        return $this->videoId;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set thumbnailUrl
     *
     * @param string $thumbnailUrl
     */
    public function setThumbnailUrl($thumbnailUrl)
    {
        $this->thumbnailUrl = $thumbnailUrl;
    }

    /**
     * Get thumbnailUrl
     *
     * @return text 
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set embedCode
     *
     * @param string $embedCode
     */
    public function setEmbedCode($embedCode)
    {
        $this->embedCode = $embedCode;
    }

    /**
     * Get embedCode
     *
     * @return string
     */
    public function getEmbedCode()
    {
        return $this->embedCode;
    }

    /**
     * Set videoGallery
     *
     * @param VideoGallery $videoGallery
     */
    public function setVideoGallery($videoGallery)
    {
        $this->videoGallery = $videoGallery;
    }

    /**
     * Get videoGallery
     *
     * @return VideoGallery 
     */
    public function getVideoGallery()
    {
        return $this->videoGallery;
    }
}
