<?php

namespace ImiBorbas\VideoGalleryBundle\Importer;

use Doctrine\ORM\EntityManager;

use ImiBorbas\VideoGalleryBundle\Entity\VideoGallery;
use ImiBorbas\VideoGalleryBundle\Entity\Video;

class YoutubeImporter
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Creates a new YoutubeImporter instance.
     *
     * @param EntityManager $entityManager Doctrine EntityManager instance
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Updates the given VideoGallery with videos from YouTube's web API.
     *
     * @throws \InvalidArgumentException
     *
     * @param VideoGallery $videoGallery       VideoGallery to be updated
     * @param string       $thumbnailDirectory Target directory for thumbnails
     *
     * @return void
     */
    public function updateVideoGallery(VideoGallery $videoGallery, $thumbnailDirectory)
    {
        if (!is_dir($thumbnailDirectory)) {
            throw new \InvalidArgumentException('Invalid thumbnail directory supplied.');
        }

        $videoRepository = $this->entityManager->getRepository('ImiBorbasVideoGalleryBundle:Video');
        
        $feed = file_get_contents($this->getFeedUrl($videoGallery->getPlaylistId()));

        $dom = new \DOMDocument();
        $dom->loadXML($feed);

        $entries = $dom->getElementsByTagName('entry');
        $videoIds = array();

        foreach ($entries as $entry) {
            $state = $entry->getElementsByTagName('state')->item(0);

            if ($state) {
                continue;
            }

            $importedVideo = array();

            $importedVideo['id'] = $entry->getElementsByTagName('videoid')->item(0)->nodeValue;
            $importedVideo['title'] = $entry->getElementsByTagName('title')->item(0)->nodeValue;
            $importedVideo['description'] = $entry->getElementsByTagName('description')->item(0)->nodeValue;
            $importedVideo['position'] = $entry->getElementsByTagName('position')->item(0)->nodeValue;
            
            $thumbnails = $entry->getElementsByTagName('thumbnail');
            
            $importedVideo['thumbnailUrl'] = $thumbnails->item(0)->getAttribute('url');
            
            foreach ($thumbnails as $thumbnail) {
                if ($thumbnail->getAttribute('yt:name') == 'hqdefault') {
                    $importedVideo['thumbnailUrl'] = $thumbnail->getAttribute('url');
                }
            }
            
            $videoIds[] = $importedVideo['id'];
            
            $video = $videoRepository->findOneBy(array(
                'videoId'      => $importedVideo['id'],
                'videoGallery' => $videoGallery->getId()
            ));
            
            if (!$video) {
                $video = new Video();
                $video->setVideoId($importedVideo['id']);
                $video->setVideoGallery($videoGallery);
                $video->setTitle($importedVideo['title']);
                $video->setEmbedCode($this->getVideoEmbedCode($importedVideo['id']));
            }
            
            file_put_contents(
                $this->getThumbnailPath($thumbnailDirectory, $importedVideo['id']),
                file_get_contents($importedVideo['thumbnailUrl'])
            );

            $video->setDescription($importedVideo['description']);
            $video->setThumbnailUrl($this->getThumbnailPath($thumbnailDirectory, $importedVideo['id']));
            $video->setPosition($importedVideo['position']);

            $this->entityManager->persist($video);
            $this->entityManager->flush();
        }
        
        $videoRepository->deleteFromVideoGalleryExcludingVideoIds($videoGallery->getId(), $videoIds);
    }
    
    private function getFeedUrl($playlistId)
    {
        return "https://gdata.youtube.com/feeds/api/playlists/$playlistId?v=2";
    }
    
    private function getThumbnailPath($thumbnailDirectory, $videoId)
    {
        return  $thumbnailDirectory . DIRECTORY_SEPARATOR . "$videoId.jpg";
    }

    private function getVideoEmbedCode($videoId)
    {
        return '<iframe id="player" type="text/html" width="640" height="390"
            src="http://www.youtube.com/embed/' . $videoId . '"
            frameborder="0"></iframe>';
    }
}
