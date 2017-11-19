<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\Scenario\GameOverScenario;
use GameBundle\Scene\ScenarioInterface;
use GameBundle\Entity\Guard;
use GameBundle\Entity\Player;
use GameBundle\Objects\Inside;
use GameBundle\Service\LifecycleService;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class FightChoice extends AbstractChoice
{
    const IDENTIFIER = 'fight';

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
    public function __construct(LifecycleService $lifecycleService, Player $player, Guard $guard, Inside $inside)
    {
        parent::__construct($lifecycleService, $player);

        $this->guard = $guard;
        $this->inside = $inside;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Fight!';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        if ($this->lifecycleService->fight($this->player, $this->guard)){
            return new InsideScenario($this->lifecycleService, $this->player, $this->inside);
        } else {
            return new GameOverScenario($this->lifecycleService, $this->player);
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