<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\ScenarioInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ReplayChoice extends AbstractChoice
{
    const IDENTIFIER = 'replay';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Play again';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        return $this->lifecycleService->replay($this->player);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}