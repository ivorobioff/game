<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Artifact;
use GameBundle\Entity\Health;
use GameBundle\Entity\Hint;
use GameBundle\Entity\Solution;
use GameBundle\Entity\Weapon;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ArtifactResourceFactory
{
    /**
     * @param Artifact $artifact
     * @return ArtifactResource
     */
    public function create(Artifact $artifact) : ArtifactResource
    {
        if ($artifact instanceof Solution){
            return new SolutionResource($artifact);
        }

        if ($artifact instanceof Hint){
            return new HintResource($artifact);
        }

        if ($artifact instanceof Weapon){
            return new WeaponResource($artifact);
        }

        if ($artifact instanceof Health){
            return new HealthResource($artifact);
        }

        throw new \RuntimeException('Unknow artifact');
    }
}