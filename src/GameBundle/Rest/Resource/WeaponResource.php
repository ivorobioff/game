<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Weapon;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class WeaponResource extends ArtifactResource
{
    /**
     * @var string
     */
    private $name = 'Weapon';

    /**
     * @var int
     */
    private $power;

    public function __construct(Weapon $weapon)
    {
        parent::__construct($weapon);

        $this->power = $weapon->getPower();
    }
}