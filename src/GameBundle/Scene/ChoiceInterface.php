<?php
namespace GameBundle\Scene;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface ChoiceInterface
{
    /**
     * It tells the user what this choice will do
     *
     * @return string
     */
    public function getTitle() : string;

    /**
     * Identifier of the choice. It should be unique across all choices in a scenario,
     * but not across all choices in the game
     *
     * @return string
     */
    public function getIdentifier() : string;

    /**
     * Performs an action and decides what scenario goes next. It can return null, that means the game is finished.
     * Normally, this happens when quiting the game.
     *
     * @return null|ScenarioInterface
     */
    public function forward() : ?ScenarioInterface;
}