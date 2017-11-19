<?php
namespace GameBundle\Rest\Resource;

use GameBundle\Entity\Token;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class TokenResource
{
    /**
     * @var Token
     */
    private $secret;

    /**
     * @param Token $token
     */
    public function __construct(Token $token)
    {
        $this->secret = $token->getSecret();
    }
}