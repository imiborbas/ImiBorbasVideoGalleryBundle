<?php

namespace ImiBorbas\VideoGalleryBundle\Importing;

/**
 * Default implementation of a VideoImporterFactory.
 */
class DefaultVideoImporterFactory implements VideoImporterFactory
{
    /**
     * Creates the appropriate VideoImporter object for the given $videoServiceName.
     *
     * @throws \Exception
     *
     * @param string $videoServiceName Name of the video providing service
     *
     * @return \ImiBorbas\VideoGalleryBundle\Importing\VideoImporter
     */
    public function createVideoImporter($videoServiceName)
    {
        switch ($videoServiceName) {
            case 'youtube':
                return new YoutubeImporter();
                break;
        }

        throw new \Exception('Importer for video service not found.');
    }
}
