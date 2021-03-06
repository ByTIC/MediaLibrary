<?php

namespace ByTIC\MediaLibrary\Media\Manipulators\Images\Drivers;

use ByTIC\MediaLibrary\Conversions\Manipulations\Manipulation;
use ByTIC\MediaLibrary\Conversions\Manipulations\ManipulationSequence;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

/**
 * Class ImagineDriver.
 */
class ImagineDriver extends AbstractDriver
{
    /**
     * {@inheritdoc}
     */
    public function manipulate($data, ManipulationSequence $manipulations, $extenstion)
    {
        $image = $this->makeImage($data);
        $this->performManipulations($image, $manipulations);

        $image->encode($extenstion);

        return $image->__toString();
    }

    /**
     * @param $data
     *
     * @return Image
     */
    public function makeImage($data)
    {
        $manager = new ImageManager();

        return $manager->make($data);
    }

    /**
     * @param Image                $image
     * @param ManipulationSequence $manipulations
     */
    protected function performManipulations($image, $manipulations)
    {
        foreach ($manipulations as $manipulation) {
            $this->performManipulation($image, $manipulation);
        }
    }

    /**
     * @param Image        $image
     * @param Manipulation $manipulation
     */
    protected function performManipulation($image, $manipulation)
    {
        $methodName = $manipulation->getName();
        $image->{$methodName}(...$manipulation->getAttributes());
    }
}
