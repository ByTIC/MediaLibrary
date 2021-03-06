<?php

namespace ByTIC\MediaLibrary\Support;

use ByTIC\MediaLibrary\Models\MediaProperties\MediaProperties;
use ByTIC\MediaLibrary\Models\MediaRecords\MediaRecords;
use Nip\Records\Locator\ModelLocator;

/**
 * Class MediaModels
 * @package ByTIC\MediaLibrary\Support
 */
class MediaModels
{
    protected static $models = [];

    /**
     * @return MediaRecords
     */
    public static function records()
    {
        return static::getModels('records', MediaRecords::class);
    }

    /**
     * @return MediaProperties
     */
    public static function properties()
    {
        return static::getModels('properties', MediaProperties::class);
    }

    /**
     * @param string $type
     * @param string $default
     * @return mixed|\Nip\Records\AbstractModels\RecordManager
     */
    protected static function getModels($type, $default)
    {
        if (!isset(static::$models[$type])) {
            $modelManager = static::getConfigVar($type, $default);
            return static::$models[$type] = ModelLocator::get($modelManager);
        }

        return static::$models[$type];
    }

    /**
     * @param string $type
     * @param null|string $default
     * @return string
     */
    protected static function getConfigVar($type, $default = null)
    {
        if (!function_exists('config')) {
            return $default;
        }
        $varName = 'media-library.media_models.' . $type;
        $config = config();
        if ($config->has($varName)) {
            return $config->get($varName);
        }
        return $default;
    }
}
