<?php

namespace ByTIC\MediaLibrary\HasMedia\StandardCollections;

use ByTIC\MediaLibrary\Collections\Collection;
use ByTIC\MediaLibrary\FileAdder\FileAdder;
use ByTIC\MediaLibrary\Media\Media;

/**
 * Trait StandardCollectionsShortcodes.
 */
trait FilesShortcodes
{
    /**
     * @return Collection|Media[]
     *
     * @deprecated Use getFiles
     */
    public function findFiles()
    {
        return $this->getFiles();
    }

    /**
     * @return Collection|Media[]
     */
    public function getFiles()
    {
        return $this->getMedia('files');
    }

    /**
     * @param $file
     *
     * @return FileAdder
     */
    public function addFile($file)
    {
        return $this->addMediaToCollection($file, 'files');
    }

    /**
     * @param $content
     * @param $name
     */
    public function addFileFromContent($content, $name)
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'bytic-media-library');
        file_put_contents($tmpFile, $content);

        $fileAdder = $this->addMedia($tmpFile);
        $fileAdder->setFileName($name);
        $fileAdder->toMediaCollection('files');

        unlink($tmpFile);
        return $fileAdder;
    }

    /**
     * @param string $collectionName
     * @param array  $filters
     *
     * @return Collection
     */
    abstract public function getMedia(string $collectionName = 'default', $filters = []): Collection;
}
