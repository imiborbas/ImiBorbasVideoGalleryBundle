<?php

namespace ImiBorbas\VideoGalleryBundle\Importing;

interface VideoImporter
{
    /**
     * Retrieves a playlist from a web service.
     *
     * @abstract
     *
     * @param string $playistId Identifier of the playlist to be updated
     *
     * @return ImportedVideoCollection
     */
    public function importPlaylist($playlistId);
}
