<?php
namespace GameBundle\Rest\Security;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface UsernameByTokenProviderInterface
{
    /**
     * @param string $token
     * @return string
     */
    public function getUsernameByToken(string  $token) : ?string;
}