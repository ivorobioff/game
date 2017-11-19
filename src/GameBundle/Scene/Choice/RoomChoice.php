<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Entity\Player;
use GameBundle\Entity\Room;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class RoomChoice extends AbstractChoice
{
    const PREFIX_IDENTIFIER = 'room-';

    /**
     * @var Room
     */
    private $room;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Room $room
     */
    public function __construct(LifecycleService $lifecycleService, Player $player, Room $room)
    {
        parent::__construct($lifecycleService, $player);

        $this->room = $room;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Enter room "' . $this->room->getName().'"';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        $inside = $this->lifecycleService->enter($this->player, $this->room);

        return new InsideScenario($this->lifecycleService, $this->player, $inside);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::PREFIX_IDENTIFIER.$this->room->getId();
    }
}