<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\ChoiceInterface;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class InsideScenario extends AbstractScenario
{
    const IDENTIFIER = 'inside';

    /**
     * @var ScenarioInterface
     */
    private $determinedScenario;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Inside $inside
     */
    public function __construct(LifecycleService $lifecycleService, Player $player, Inside $inside)
    {
        parent::__construct($lifecycleService, $player);

        $this->determinedScenario = $this->determineScenario($inside);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->determinedScenario->getDescription();
    }

    /**
     * @return ChoiceInterface[]
     */
    public function getChoices(): array
    {
        return $this->determinedScenario->getChoices();
    }

    /**
     * @param Inside $inside
     * @return ScenarioInterface
     */
    public function determineScenario(Inside $inside) : ScenarioInterface
    {
        if ($challenge = $inside->getChallenge()){
            return new ChallengeScenario($this->lifecycleService, $this->player, $inside->getChallenge(), $inside);
        }

        if ($inside->getGuard()){
            return new GuardScenario($this->lifecycleService, $this->player, $inside->getGuard(), $inside);
        }

        if ($inside->getRoom()->getFinal()){
            return new EndScenario($this->lifecycleService, $this->player);
        }

        return new RoomScenario($this->lifecycleService, $this->player, $inside);
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