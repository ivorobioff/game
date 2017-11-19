<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Artifact;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ArtifactResource
{
    /**
     * @var string
     */
    private $description;

    /**
     * @param Artifact $artifact
     */
    public function __construct(Artifact $artifact)
    {
        $this->description = $artifact->getDescription();
    }
}