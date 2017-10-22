<?php

namespace ByTIC\MediaLibrary\Media\Manipulators\Images;

use ByTIC\MediaLibrary\Conversions\Conversion;
use ByTIC\MediaLibrary\Media\Manipulators\AbstractManipulator;
use ByTIC\MediaLibrary\Media\Manipulators\Images\Drivers\AbstractDriver;
use ByTIC\MediaLibrary\Media\Manipulators\Images\Drivers\ImagineDriver;
use ByTIC\MediaLibrary\Media\Media;
use Nip\Collection;

/**
 * Class ImageManipulator
 * @package ByTIC\MediaLibrary\Media\Manipulator
 */
class ImageManipulator extends AbstractManipulator
{
    protected $driver = null;

    /**
     * @param Media $media
     * @param Conversion $conversion
     */
    public function performConversion(Media $media, Conversion $conversion)
    {
        $imageContent = $this->getDriver()->manipulate(
            $media->getFile()->read(), $conversion->getManipulations(), $media->getExtension()
        );

        $path = $media->getPath($conversion->getName());
        $media->getCollection()->getFilesystem()->put($path, $imageContent);

    }

    /**
     * @return AbstractDriver
     */
    public function getDriver()
    {
        if ($this->driver === null) {
            $this->driver = $this->newDriver();
        }
        return $this->driver;
    }

    /**
     * @return ImagineDriver
     */
    protected function newDriver()
    {
        return new ImagineDriver();
    }

    /**
     * @return bool
     */
    public function requirementsAreInstalled(): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function supportedExtensions(): Collection
    {
        return new Collection(['png', 'jpg', 'jpeg', 'gif']);
    }

    /**
     * @inheritdoc
     */
    public function supportedMimeTypes(): Collection
    {
        return new Collection(['image/jpeg', 'image/gif', 'image/png']);
    }
}