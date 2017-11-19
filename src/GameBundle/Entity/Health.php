<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 */
class Health extends Artifact
{
    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $percentage;

    /**
     * @param int $percentage
     */
    public function setPercentage(int $percentage)
    {
        $this->percentage = $percentage;
    }

    /**
     * @return int
     */
    public function getPercentage() : int
    {
        return $this->percentage;
    }
}