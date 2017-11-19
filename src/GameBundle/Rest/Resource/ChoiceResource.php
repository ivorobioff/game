<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Scene\ChoiceInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ChoiceResource
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @param ChoiceInterface $choice
     */
    public function __construct(ChoiceInterface $choice)
    {
        $this->identifier = $choice->getIdentifier();
        $this->title = $choice->getTitle();
    }
}