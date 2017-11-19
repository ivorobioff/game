<?php
namespace GameBundle\Scene\Choice;

use GameBundle\Scene\ChoiceInterface;
use GameBundle\Scene\ScenarioInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class QuitChoice implements ChoiceInterface
{
    const IDENTIFIER = 'quit';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return 'Quit';
    }

    /**
     * @return null|ScenarioInterface
     */
    public function forward(): ?ScenarioInterface
    {
        return null;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return self::IDENTIFIER;
    }
}