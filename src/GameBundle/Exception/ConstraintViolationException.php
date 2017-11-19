<?php
namespace GameBundle\Exception;

use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ConstraintViolationException extends UnprocessableEntityHttpException
{
    /**
     * @var ConstraintViolationListInterface|ConstraintViolationInterface[]
     */
    private $validationErrors;

    /**
     * @param ConstraintViolationListInterface $validationErrors
     */
    public function __construct(ConstraintViolationListInterface $validationErrors)
    {
        parent::__construct('The provided data is invalid');

        $this->validationErrors = $validationErrors;
    }

    public function getValidationErrors() : ConstraintViolationListInterface
    {
        return $this->validationErrors;
    }
}