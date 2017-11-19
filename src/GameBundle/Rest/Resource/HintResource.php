<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Hint;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class HintResource extends ArtifactResource
{
    /**
     * @var string
     */
    private $name = 'Hint';

    public function __construct(Hint $hint)
    {
        parent::__construct($hint);
    }
}