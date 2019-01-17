<?php

namespace ByTIC\MediaLibrary;

use ByTIC\MediaLibrary\Loaders\Filesystem;
use ByTIC\MediaLibrary\Loaders\LoaderInterface;
use Nip\Container\ServiceProvider\AbstractSignatureServiceProvider;

/**
 * Class MediaLibraryServiceProvider
 * @package ByTIC\MediaLibrary
 */
class MediaLibraryServiceProvider extends AbstractSignatureServiceProvider
{

    /**
     * @inheritdoc
     */
    public function register()
    {
        $this->getContainer()->set(LoaderInterface::class, Filesystem::class);
    }

    /**
     * @inheritdoc
     */
    public function provides()
    {
        return [LoaderInterface::class];
    }
}