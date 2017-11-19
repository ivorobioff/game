<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="challenges")
 */
class Challenge
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
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Room", inversedBy="challenge")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Room
     */
    private $room;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Solution", mappedBy="challenge")
     *
     * @var Solution
     */
    private $solution;

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
     * @param Room $room
     */
    public function setRoom(Room $room)
    {
        $this->room = $room;
    }

    /**
     * @return Room
     */
    public function getRoom() : Room
    {
        return $this->room;
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
     * @param Solution $solution
     */
    public function setSolution(Solution $solution)
    {
        $this->solution = $solution;
    }

    /**
     * @return Solution
     */
    public function getSolution() : Solution
    {
        return $this->solution;
    }
}