<?php

namespace ImiBorbas\VideoGalleryBundle\Importing;

/**
 * Class for fetching videos from YouTube.
 */
class YoutubeImporter implements VideoImporter
{
    /**
     * Retrieves a playlist from YouTube's data API.
     *
     * @param string $playistId Identifier of the playlist to be updated
     *
     * @return ImportedVideoCollection
     */
    public function importPlaylist($playlistId)
    {
        $feed = file_get_contents($this->getFeedUrl($playlistId));

        $dom = new \DOMDocument();
        $dom->loadXML($feed);

        $entries = $dom->getElementsByTagName('entry');

        $importedVideos = new ImportedVideoCollection();

        foreach ($entries as $entry) {
            if ($this->isVideoUnavailable($entry)) {
                continue;
            }

            $importedVideos->append($this->createImportedVideo($entry));
        }

        return $importedVideos;
    }

    private function getFeedUrl($playlistId)
    {
        return "https://gdata.youtube.com/feeds/api/playlists/$playlistId?v=2";
    }
    
    private function isVideoUnavailable(\DOMNode $videoEntry)
    {
        return (true == $videoEntry->getElementsByTagName('state')->item(0));
    }

    private function createImportedVideo(\DOMNode $videoEntry)
    {
        $importedVideo = new ImportedVideo();
        $importedVideo->id = $videoEntry->getElementsByTagName('videoid')->item(0)->nodeValue;
        $importedVideo->title = $videoEntry->getElementsByTagName('title')->item(0)->nodeValue;
        $importedVideo->description = $videoEntry->getElementsByTagName('description')->item(0)->nodeValue;
        $importedVideo->thumbnailUrl = $videoEntry->getElementsByTagName('thumbnail')->item(0)->getAttribute('url');
        $importedVideo->position = $videoEntry->getElementsByTagName('position')->item(0)->nodeValue;
        $importedVideo->embedCode = $this->getVideoEmbedCode($importedVideo->id);

        return $importedVideo;
    }

    private function getVideoEmbedCode($videoId)
    {
        return '<iframe id="player" type="text/html" width="640" height="390"
            src="http://www.youtube.com/embed/' . $videoId . '"
            frameborder="0"></iframe>';
    }
}
