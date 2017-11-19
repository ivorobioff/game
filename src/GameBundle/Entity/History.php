<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="history")
 */
class History
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
     * @ORM\ManyToOne(targetEntity="\GameBundle\Entity\Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Player
     */
    private $player;

    /**
     * @ORM\ManyToOne(targetEntity="\GameBundle\Entity\Room")
     * @ORM\JoinColumn(name="room_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Room
     */
    private $room;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $enteredAt;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $guarded = true;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $collected = false;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $solved = false;

    public function __construct()
    {
        $this->enteredAt = new \DateTime();
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
     * @param Player $player
     */
    public function setPlayer(Player $player)
    {
        $this->player = $player;
    }

    /**
     * @return Player
     */
    public function getPlayer() : Player
    {
        return $this->player;
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

    public function setEnteredAt(\DateTime $enteredAt)
    {
        $this->enteredAt = $enteredAt;
    }

    /**
     * @return \DateTime
     */
    public function getEnteredAt() : \DateTime
    {
        return $this->enteredAt;
    }

    /**
     * @param bool $flag
     */
    public function setGuarded(bool $flag)
    {
        $this->guarded = $flag;
    }

    /**
     * @return bool
     */
    public function getGuarded() : bool
    {
        return $this->guarded;
    }

    /**
     * @param bool $flag
     */
    public function setCollected(bool $flag)
    {
        $this->collected = $flag;
    }

    /**
     * @return bool
     */
    public function getCollected() : bool
    {
        return $this->collected;
    }

    /**
     * @param bool $flag
     */
    public function setSolved(bool $flag)
    {
        $this->solved = $flag;
    }

    /**
     * @return bool
     */
    public function getSolved() : bool
    {
        return $this->solved;
    }
}