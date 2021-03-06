<?php

namespace ByTIC\MediaLibrary\Validation\Constraints\Traits;

use ByTIC\MediaLibrary\Validation\Constraints\AbstractConstraint;
use ByTIC\MediaLibrary\Validation\Validators\AbstractValidator;

/**
 * Trait HasValidatorTrait.
 */
trait HasConstraintTrait
{
    /**
     * @var null|AbstractConstraint
     */
    protected $constraint = null;

    /**
     * @var string
     */
    protected $contraintName = null;

    /**
     * @return AbstractConstraint|null
     */
    public function getConstraint()
    {
        if ($this->constraint === null) {
            $this->initConstraint();
        }

        return $this->constraint;
    }

    /**
     * @param AbstractConstraint|null $constraint
     */
    public function setConstraint($constraint)
    {
        $this->constraint = $constraint;
    }

    protected function initConstraint()
    {
        $constraint = $this->generateConstraint();
        $this->setConstraint($constraint);
    }

    /**
     * @return AbstractConstraint
     */
    protected function generateConstraint()
    {
        $constraint = $this->newConstraint();
        $this->hydrateConstraint($constraint);

        return $constraint;
    }

    /**
     * @return AbstractConstraint
     */
    protected function newConstraint()
    {
        $class = $this->getValidator()->getConstraintClassName();

        return new $class();
    }

    /**
     * @return mixed
     */
    public function getContraintName()
    {
        return $this->contraintName;
    }

    /**
     * @param string $contraintName
     */
    public function setContraintName(string $contraintName)
    {
        $this->contraintName = $contraintName;
    }

    /**
     * @return AbstractValidator
     */
    abstract protected function getValidator();

    /**
     * @param AbstractConstraint $constraint
     */
    protected function hydrateConstraint($constraint)
    {
        $contraintName = $this->getContraintName();
        if ($contraintName) {
            $constraint->setName($contraintName);
        }
        $constraint->init();
    }
}
