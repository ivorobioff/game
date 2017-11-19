<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Scene\Choice\FightChoice;
use GameBundle\Entity\Guard;
use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class GuardScenario extends AbstractScenario
{
    const IDENTIFIER = 'guard';

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var Inside
     */
    private $inside;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Guard $guard
     * @param Inside $inside
     */
    public function __construct(
        LifecycleService $lifecycleService,
        Player $player,
        Guard $guard,
        Inside $inside
    ) {
        parent::__construct($lifecycleService, $player);

        $this->guard = $guard;
        $this->inside = $inside;
    }

    /**
     * @return ChoiceInterface[]
     */
    public function getChoices(): array
    {
        $choices = parent::getChoices();

        $fightChoice = new FightChoice($this->lifecycleService, $this->player, $this->guard, $this->inside);

        $choices[$fightChoice->getIdentifier()] = $fightChoice;

        return $choices;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        $power = $this->guard->getPower();

        return 'You faced a guard "'.$this->guard->getName().'" in this room. His power is "'
            .$power.'". Your power is "'
            .$this->player->getOverallPower().'". You must decide what you want to do next!';
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
        return new static($lifecycleService, $player, $inside->getGuard(), $inside);
    }
}