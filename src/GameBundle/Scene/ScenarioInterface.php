<?php
namespace GameBundle\Scene;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface ScenarioInterface
{
    /**
     * Identifier of a scenario. It should be unique across all scenarios in the game
     *
     * @return string
     */
    public function getIdentifier() : string;

    /**
     * It describes the current situation
     *
     * @return string
     */
    public function getDescription() : string;

    /**
     * Choices provided by the scenario
     *
     * @return ChoiceInterface[]
     */
    public function getChoices() : array;

    /**
     * Executes a choice based on the provided choice identifier
     *
     * @param string $identifier
     * @return null|ScenarioInterface
     */
    public function choose(string $identifier) : ?ScenarioInterface;
}