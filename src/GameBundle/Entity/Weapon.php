<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 */
class Weapon extends Artifact
{
    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $power;

    /**
     * @param int $power
     */
    public function setPower(int $power)
    {
        $this->power = $power;
    }

    /**
     * @return int
     */
    public function getPower() : int
    {
        return $this->power;
    }
}