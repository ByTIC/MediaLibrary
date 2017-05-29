<?php

namespace ByTIC\MediaLibrary\HasMedia;

use ByTIC\MediaLibrary\Collections\Collection;
use ByTIC\MediaLibrary\Media\Media;

/**
 * Trait StandardCollectionsShortcodes
 * @package ByTIC\MediaLibrary\HasMedia
 *
 * @method Collection getMedia(string $collectionName = 'default', $filters = []): Collection
 */
trait StandardCollectionsShortcodes
{

    /**
     * @return Media
     */
    public function getImage()
    {
        return $this->getMedia('images')->getDefaultMedia();
    }

    /**
     * @return Media
     */
    public function getLogo()
    {
        return $this->getMedia('logos')->getDefaultMedia();
    }

    /**
     * @return bool
     */
    public function hasLogo()
    {
        return $this->getMedia('logos')->count() > 0;
    }

    /**
     * @return Media
     */
    public function getCover()
    {
        return $this->getMedia('covers')->getDefaultMedia();
    }

}