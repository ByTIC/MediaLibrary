<?php

namespace ByTIC\MediaLibrary\Collections\Traits;

use ByTIC\MediaLibrary\HasMedia\HasMediaTrait;
use ByTIC\MediaLibrary\Media\Media;
use Nip\Records\Record;

/**
 * Trait HasDefaultMediaTrait
 * @package ByTIC\MediaLibrary\Collections\Traits
 *
 * @method HasMediaTrait|Record getRecord
 */
trait HasDefaultMediaTrait
{

    /**
     * @return Media
     */
    public function getDefaultMedia()
    {
        if (count($this->items)) {
            return reset($this->items);
        }
        return $this->compileDefaultMedia();
    }

    /**
     * @return Media
     */
    protected function compileDefaultMedia()
    {
        $media = $this->newMedia();
        return $media;
    }

    /**
     * @return string
     */
    public function getDefaultMediaUrl()
    {
        if (method_exists($this->getRecord(), 'getDefaultMediaUrl')) {
            return $this->getRecord()->getDefaultMediaUrl($this);
        }

        if (method_exists($this->getRecord()->getManager(), 'getDefaultMediaUrl')) {
            return $this->getRecord()->getManager()->getDefaultMediaUrl($this);
        }

        return $this->getDefaultMediaGenericUrl();
    }

    /**
     * @return string
     */
    public function getDefaultMediaGenericUrl()
    {
        return '/assets/images/'
            . $this->getRecord()->getManager()->getTable() . '/'
            . $this->getDefaultFileName();
    }

    /**
     * @return string
     */
    protected function getDefaultFileName()
    {
        $name = inflector()->singularize($this->getName());
        $extension = $this->getName() == 'logos' ? 'png' : 'jpg';
        return $name . '.' . $extension;
    }
}