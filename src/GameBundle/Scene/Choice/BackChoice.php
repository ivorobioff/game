<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\Scenario\InsideScenario;
use GameBundle\Scene\ScenarioInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class BackChoice extends AbstractChoice
{
    const IDENTIFIER = 'back';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Go back';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        return new InsideScenario($this->lifecycleService, $this->player, $this->lifecycleService->back($this->player));
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}