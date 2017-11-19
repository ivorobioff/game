<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Health;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class HealthResource extends ArtifactResource
{
    /**
     * @var string
     */
    private $name = 'Health';

    /**
     * @var int
     */
    private $percentage;

    /**
     * @param Health $health
     */
    public function __construct(Health $health)
    {
        parent::__construct($health);

        $this->percentage = $health->getPercentage();
    }
}