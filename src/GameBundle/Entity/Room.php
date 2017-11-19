<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="rooms")
 *
 */
class Room
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
     * @ORM\Column(type = "string")
     *
     * @var string
     */
    private $name;

    /**
     *
     * @ORM\OneToMany(targetEntity="\GameBundle\Entity\Room", mappedBy="previousRoom")
     *
     * @var Room[]
     */
    private $nextRooms;

    /**
     * @ORM\ManyToOne(targetEntity="\GameBundle\Entity\Room", inversedBy="nextRooms")
     * @ORM\JoinColumn(name="previous_room_id", referencedColumnName="id", onDelete="SET NULL")
     *
     * @var Room
     */
    private $previousRoom;

    /**
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Guard", mappedBy="room")
     *
     * @var Guard
     */
    private $guard;

    /**
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Challenge", mappedBy="room")
     *
     * @var Challenge $challenge
     */
    private $challenge;

    /**
     * @ORM\OneToMany(targetEntity="\GameBundle\Entity\Artifact", mappedBy="room")
     *
     * @var Artifact[]
     */
    private $artifacts;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $final = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $initial = false;


    public function __construct()
    {
        $this->nextRooms = new ArrayCollection();
        $this->artifacts = new ArrayCollection();
    }

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
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param null|Room $room
     */
    public function setPreviousRoom(?Room $room)
    {
        $room->addNextRoom($this);
        $this->previousRoom = $room;
    }

    /**
     * @return null|Room
     */
    public function getPreviousRoom() : ?Room
    {
        return $this->previousRoom;
    }

    /**
     * @param Room $room
     */
    public function addNextRoom(Room $room)
    {
        $this->nextRooms->add($room);
    }

    /**
     * @return Room[]|\Traversable|\Countable
     */
    public function getNextRooms()
    {
        return $this->nextRooms;
    }

    /**
     * @param null|Guard $guard
     */
    public function setGuard(?Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @return null|Guard
     */
    public function getGuard() : ?Guard
    {
        return $this->guard;
    }

    /**
     * @param null|Challenge $challenge
     */
    public function setChallenge(?Challenge $challenge)
    {
        $this->challenge = $challenge;
    }

    /**
     * @return null|Challenge
     */
    public function getChallenge() : ?Challenge
    {
        return $this->challenge;
    }

    /**
     * @param Artifact $artifact
     */
    public function addArtifact(Artifact $artifact)
    {
        $this->artifacts->add($artifact);
    }

    /**
     * @return Artifact[]
     */
    public function getArtifacts()
    {
        return $this->artifacts;
    }

    /**
     * @param bool $flag
     */
    public function setFinal(bool $flag)
    {
        $this->final = $flag;
    }

    /**
     * @return bool
     */
    public function getFinal() : bool
    {
        return $this->final;
    }

    /**
     * @param bool $flag
     */
    public function setInitial(bool $flag)
    {
        $this->initial = $flag;
    }

    /**
     * @return bool
     */
    public function getInitial() : bool
    {
        return $this->initial;
    }
}