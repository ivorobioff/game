<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Scene\ScenarioInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ScenarioResource
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var ChoiceResource[]
     */
    private $choices = [];

    public function __construct(ScenarioInterface $scenario)
    {
        $this->description = $scenario->getDescription();

        foreach ($scenario->getChoices() as $choice){
            $this->choices[] = new ChoiceResource($choice);
        }
    }
}