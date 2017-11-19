<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;


/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="artifacts")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "artifact" = "\GameBundle\Entity\Artifact",
 *     "weapon" = "\GameBundle\Entity\Weapon",
 *     "solution" = "\GameBundle\Entity\Solution",
 *     "health" = "\GameBundle\Entity\Health",
 *     "hint" = "\GameBundle\Entity\Hint"
 * })
 */
abstract class Artifact
{
    /**
     * @ORM\Id
     * @ORM\Column(type = "integer")
     * @ORM\GeneratedValue
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="\GameBundle\Entity\Room", inversedBy="artifacts")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Room
     */
    private $room;

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return null|string
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $room->addArtifact($this);
        $this->room = $room;
    }

    /**
     * @return Room
     */
    public function getRoom() : Room
    {
        return $this->room;
    }
}