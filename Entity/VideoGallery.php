<?php

namespace ImiBorbas\VideoGalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImiBorbas\VideoGalleryBundle\Entity\VideoGallery
 *
 * @ORM\Table(name="video_gallery")
 * @ORM\Entity(repositoryClass="ImiBorbas\VideoGalleryBundle\Entity\VideoGalleryRepository")
 */
class VideoGallery
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
     * @var string $videoServiceName
     *
     * @ORM\Column(name="video_service_name", type="string", length=255)
     */
    private $videoServiceName;

    /**
     * @var string $playlistId
     *
     * @ORM\Column(name="playlist_id", type="string", length=255)
     */
    private $playlistId;
    
    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $slug
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var array $videos
     *
     * @ORM\OneToMany(targetEntity="Video", mappedBy="videoGallery", cascade={"remove"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $videos;

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
     * Set videoServiceName
     *
     * @param string $videoServiceName
     */
    public function setVideoServiceName($videoServiceName)
    {
        $this->videoServiceName = $videoServiceName;
    }

    /**
     * Get videoServiceName
     *
     * @return string
     */
    public function getVideoServiceName()
    {
        return $this->videoServiceName;
    }

    /**
     * Set playlistId
     *
     * @param string $playlistId
     */
    public function setPlaylistId($playlistId)
    {
        $this->playlistId = $playlistId;
    }

    /**
     * Get playlistId
     *
     * @return string 
     */
    public function getPlaylistId()
    {
        return $this->playlistId;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }
    
    /**
     * Get videos
     *
     * @return array 
     */
    public function getVideos()
    {
        return $this->videos;
    }
}