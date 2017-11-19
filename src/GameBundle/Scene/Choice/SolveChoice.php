<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\Scenario\UnsolvedChallengeScenario;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Entity\Challenge;
use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class SolveChoice extends AbstractChoice
{
    const IDENTIFIER = 'solve';

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
    public function getTitle(): string
    {
        return 'I have what\'s needed';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        if ($this->lifecycleService->solve($this->player, $this->challenge)){
            return new InsideScenario($this->lifecycleService, $this->player, $this->inside);
        } else {
            return new UnsolvedChallengeScenario($this->lifecycleService, $this->player);
        }
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}