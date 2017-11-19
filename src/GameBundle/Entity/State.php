<?php
namespace GameBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="states")
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class State
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
     * @ORM\OneToOne(targetEntity="\GameBundle\Entity\Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id", onDelete="CASCADE")
     *
     * @var Player
     */
    private $player;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $scenario;

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
     * @param string $scenario
     */
    public function setScenario(?string $scenario)
    {
        $this->scenario = $scenario;
    }

    /**
     * @return string
     */
    public function getScenario() : ?string
    {
        return $this->scenario;
    }
}