<?php
namespace GameBundle\Rest\Payload;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class ChoosePayload
{
    /**
     * @JMS\Type("string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $identifier;

    /**
     * @return string
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }
}