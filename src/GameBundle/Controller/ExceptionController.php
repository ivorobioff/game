<?php
namespace GameBundle\Controller;

use GameBundle\Exception\ConstraintViolationException;
use GameBundle\Exception\InvalidLifecycleException;
use GameBundle\Exception\ValidationFailedException;
use GameBundle\Rest\Resource\ValidationErrorsResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ExceptionController extends \FOS\RestBundle\Controller\ExceptionController
{
    public function showAction(Request $request, $exception, DebugLoggerInterface $logger = null)
    {
        if ($exception instanceof ConstraintViolationException){
            return new ValidationErrorsResource($exception->getValidationErrors());
        } elseif ($exception instanceof ValidationFailedException) {
            $exception = new BadRequestHttpException($exception->getMessage());
        } elseif ($exception instanceof InvalidLifecycleException){
            $exception = new BadRequestHttpException($exception->getMessage());
        }

        return parent::showAction($request, $exception, $logger);
    }
}