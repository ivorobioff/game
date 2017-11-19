<?php
namespace GameBundle\Rest\Resource;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ValidationErrorsResource
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param ConstraintViolationListInterface|ConstraintViolationInterface[] $validationErrors
     */
    public function __construct(ConstraintViolationListInterface $validationErrors)
    {
        foreach ($validationErrors as $error){
            $this->errors[$error->getPropertyPath()] = $error->getMessage();
        }
    }
}