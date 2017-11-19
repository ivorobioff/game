<?php
namespace GameBundle\Scene;

/**
 * It tells the system that the scenario implementing this interface refers to another scenario.
 * This is useful when restoring scenarios between http requests
 *
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface ReferredScenarioAwareInterface
{
    /**
     * @return ScenarioInterface
     */
    public function getReferredScenario() : ScenarioInterface;

    /**
     * @param ScenarioInterface $scenario
     */
    public function setReferredScenario(ScenarioInterface $scenario);
}