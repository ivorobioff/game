<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Scene\Choice\BackChoice;
use GameBundle\Scene\Choice\CollectChoice;
use GameBundle\Scene\Choice\RoomChoice;
use GameBundle\Scene\ChoiceInterface;
use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class RoomScenario extends AbstractScenario
{
    const IDENTIFIER = 'room';

    /**
     * @var Inside
     */
    private $inside;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Inside $inside
     */
    public function __construct(LifecycleService $lifecycleService, Player $player, Inside $inside)
    {
        parent::__construct($lifecycleService, $player);

        $this->inside = $inside;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'You are in the room "' . $this->inside->getRoom()->getName().'". You can do the following things here:';
    }

    /**
     * @return ChoiceInterface[]
     */
    public function getChoices(): array
    {
        $choices =  parent::getChoices();

        if ($this->inside->getRoom()->getInitial()){
            unset($choices[BackChoice::IDENTIFIER]);
        }

        $rooms = $this->inside->getNextRooms();

        foreach ($rooms as $room){

            /**
             * @var RoomChoice $choice
             */
            $choice = new RoomChoice($this->lifecycleService, $this->player, $room);

            $choices[$choice->getIdentifier()] = $choice;
        }

        if (count($this->inside->getArtifacts()) > 0){
            $collectChoice = new CollectChoice($this->lifecycleService, $this->player, $this->inside);
            $choices[$collectChoice->getIdentifier()] = $collectChoice;
        }

        return $choices;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }

    protected static function restoring(
        LifecycleService $lifecycleService,
        Player $player,
        Inside $inside
    ): ScenarioInterface {
        return new static($lifecycleService, $player, $inside);
    }
}