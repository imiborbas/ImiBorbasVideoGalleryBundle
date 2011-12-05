<?php

namespace ImiBorbas\VideoGalleryBundle\Updater;

use Doctrine\ORM\EntityManager;

use ImiBorbas\VideoGalleryBundle\Entity\VideoGallery;
use ImiBorbas\VideoGalleryBundle\Entity\Video;
use ImiBorbas\VideoGalleryBundle\Entity\VideoRepository;
use ImiBorbas\VideoGalleryBundle\Importing\VideoImporterFactory;
use ImiBorbas\VideoGalleryBundle\Importing\ImportedVideo;

class VideoGalleryUpdater
{
    private $videoImporterFactory;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Creates a new YoutubeImporter instance.
     *
     * @param VideoImporterFactory $videoImporterFactory Factory to create the proper VideoImporter
     * @param EntityManager        $entityManager        Doctrine EntityManager instance
     */
    public function __construct(VideoImporterFactory $videoImporterFactory, EntityManager $entityManager)
    {
        $this->videoImporterFactory = $videoImporterFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Updates the given VideoGallery with new data fetched from an external API.
     *
     * @param VideoGallery $videoGallery       VideoGallery to be updated
     * @param string       $thumbnailDirectory Target directory for thumbnails
     * 
     * @return void
     */
    public function updateVideoGallery(VideoGallery $videoGallery, $thumbnailDirectory)
    {
        $videoRepository = $this->entityManager->getRepository('ImiBorbasVideoGalleryBundle:Video');
        $videoImporter = $this->videoImporterFactory->createVideoImporter($videoGallery->getVideoServiceName());

        $importedVideos = $videoImporter->importPlaylist($videoGallery->getPlaylistId());

        foreach ($importedVideos as $importedVideo) {
            $this->persistVideo($importedVideo, $videoGallery, $thumbnailDirectory);
        }

        $videoRepository->deleteFromVideoGalleryExcludingVideoIds(
            $videoGallery->getId(),
            $importedVideos->getIdArray()
        );
    }

    private function persistVideo(ImportedVideo $importedVideo, VideoGallery $videoGallery, $thumbnailDirectory)
    {
        $videoRepository = $this->entityManager->getRepository('ImiBorbasVideoGalleryBundle:Video');

        $this->saveVideoThumbnail($importedVideo, $thumbnailDirectory);

        $video = $videoRepository->findOneBy(array(
            'videoId'      => $importedVideo->id,
            'videoGallery' => $videoGallery->getId()
        ));

        if (!$video) {
            $video = new Video();
            $video->setVideoId($importedVideo->id);
            $video->setVideoGallery($videoGallery);
            $video->setTitle($importedVideo->title);
            $video->setEmbedCode($importedVideo->embedCode);
        }

        $video->setDescription($importedVideo->description);
        $video->setThumbnailUrl($this->getThumbnailPath($thumbnailDirectory, $importedVideo->id));
        $video->setPosition($importedVideo->position);

        $this->entityManager->persist($video);
        $this->entityManager->flush();
    }

    private function saveVideoThumbnail($importedVideo, $thumbnailDirectory)
    {
        if (!is_dir($thumbnailDirectory)) {
            throw new \InvalidArgumentException('Invalid thumbnail directory supplied.');
        }

        file_put_contents(
            $this->getThumbnailPath($thumbnailDirectory, $importedVideo->id),
            file_get_contents($importedVideo->thumbnailUrl)
        );
    }

    private function getThumbnailPath($thumbnailDirectory, $videoId)
    {
        return  $thumbnailDirectory . DIRECTORY_SEPARATOR . "$videoId.jpg";
    }
}
