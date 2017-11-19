<?php
namespace GameBundle\Scene\Scenario;

use GameBundle\Scene\Choice\SolveChoice;
use GameBundle\Scene\ChoiceInterface;
use GameBundle\Entity\Challenge;
use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ChallengeScenario extends AbstractScenario
{
    const IDENTIFIER = 'challenge';

    /**
     * @var Challenge
     */
    private $challenge;

    /**
     * @var Inside
     */
    private $inside;

    /**
     * @param LifecycleService $lifecycleService
     * @param Player $player
     * @param Challenge $challenge
     * @param Inside $inside
     */
    public function __construct(
        LifecycleService $lifecycleService,
        Player $player,
        Challenge $challenge,
        Inside $inside
    ) {
        parent::__construct($lifecycleService, $player);

        $this->challenge = $challenge;
        $this->inside = $inside;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->challenge->getDescription();
    }

    /**
     * @return ChoiceInterface[]
     */
    public function getChoices(): array
    {
        $choices = parent::getChoices();

        $solveChoice = new SolveChoice($this->lifecycleService, $this->player, $this->challenge, $this->inside);

        $choices[$solveChoice->getIdentifier()] = $solveChoice;

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
        return new static($lifecycleService, $player, $inside->getChallenge(), $inside);
    }
}