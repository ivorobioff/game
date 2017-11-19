<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Solution;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class SolutionResource extends ArtifactResource
{
    /**
     * @var string
     */
    private $name = 'Solution';

    public function __construct(Solution $solution)
    {
        parent::__construct($solution);
    }
}