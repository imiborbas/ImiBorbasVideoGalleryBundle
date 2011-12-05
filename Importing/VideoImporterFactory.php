<?php

namespace ImiBorbas\VideoGalleryBundle\Importing;

interface VideoImporterFactory
{
    /**
     * Creates a VideoImporter instance.
     *
     * @abstract
     *
     * @param string $videoServiceName Name of the video service provider
     *
     * @return \ImiBorbas\VideoGalleryBundle\Importing\VideoImporter
     */
    public function createVideoImporter($videoServiceName);
}
