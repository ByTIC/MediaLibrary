<?php

namespace ByTIC\MediaLibrary\FileAdder;

use ByTIC\MediaLibrary\HasMedia\HasMediaTrait;
use ByTIC\MediaLibrary\HasMedia\Interfaces\HasMedia;
use ByTIC\MediaLibrary\Media\Manipulators\ManipulatorFactory;
use ByTIC\MediaLibrary\Media\Media;
use ByTIC\MediaLibrary\PathGenerator\PathGeneratorFactory;
use Nip\Filesystem\File;
use Nip\Logger\Exception;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;

/**
 * Class FileAdder
 * @package ByTIC\MediaLibrary\FileAdder
 */
class FileAdder implements FileAdderInterface
{
    use Traits\HasFileTrait;

    /** @var HasMediaTrait|HasMedia subject */
    protected $subject;

    /** @var null|\ByTIC\MediaLibrary\Media\Media */
    protected $media = null;

    /**@var string */
    protected $mediaName;

    /**@var string */
    protected $fileName;

    /**
     * Set the name of the file that is stored on disk.
     *
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $this->sanitizeFileName($fileName);
        return $this;
    }

    /**
     * @param $fileName
     *
     * @return string
     */
    protected function sanitizeFileName(string $fileName): string
    {
        return str_replace(['#', '/', '\\'], '-', $fileName);
    }

    /**
     * @param $name
     */
    public function toMediaCollection($name)
    {
        $media = $this->getMedia();
        $collection = $this->getSubject()->getMediaRepository()->getCollection($name);

        $media->setCollection($collection);

        $this->copyMediaToFilesystem();
        $this->createMediaConversions();

        $collection->appendMedia($media);
    }

    /**
     * @return Media|null
     */
    public function getMedia()
    {
        if ($this->media === null) {
            $this->setMedia($this->createMedia());
        }
        return $this->media;
    }

    /**
     * @param Media|null $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return Media
     * @throws Exception
     */
    protected function createMedia()
    {
        if (($this->getFile() instanceof SymfonyFile) === false) {
            throw new Exception(self::NO_FILE_DEFINED);
        }
        if (($this->subject instanceof HasMedia) === false) {
            throw new Exception(self::NO_SUBJECT_DEFINED);
        }
        $media = new Media();
        $media->setModel($this->getSubject());
        return $media;
    }

    /**
     * @return HasMediaTrait|HasMedia
     */
    public function getSubject(): HasMedia
    {
        return $this->subject;
    }

    /**
     * @param HasMediaTrait $subject
     *
     * @return FileAdder
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    protected function copyMediaToFilesystem()
    {
        $media = $this->getMedia();
        $destination = PathGeneratorFactory::create()::getBasePathForMediaOriginal($media);
        $destination .= DIRECTORY_SEPARATOR . $media->getCollection()->getStrategy()::makeFileName($this);

        $media->generateFileFromContent($destination, fopen($this->getPathToFile(), 'r'));

        $file = new File($media->getCollection()->getFilesystem(), $destination);
        $media->setFile($file);
    }

    protected function createMediaConversions()
    {
        $media = $this->getMedia();
        ManipulatorFactory::createForMedia($media)->performConversions(
            $this->getSubject()->getMediaConversions()->forCollection($media->getCollection()->getName()),
            $media
        );
    }

    /**
     * @return string
     */
    public function getMediaName(): string
    {
        return $this->mediaName;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }
}