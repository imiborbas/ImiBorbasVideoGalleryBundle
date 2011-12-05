<?php

namespace ImiBorbas\VideoGalleryBundle\Importing;

/**
 * Collection class for holding ImportedVideo instances.
 */
class ImportedVideoCollection extends \ArrayObject
{
    /**
     * Returns an array of the identifiers of all the ImportedVideo instances in the collection.
     *
     * @return array
     */
    public function getIdArray()
    {
        $idArray = array();

        foreach ($this as $importedVideo) {
            $idArray[] = $importedVideo->id;
        }

        return $idArray;
    }
}
