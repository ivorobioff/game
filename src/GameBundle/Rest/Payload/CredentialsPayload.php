<?php
namespace GameBundle\Rest\Payload;

use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CredentialsPayload
{
    /**
     * @JMS\Type("string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $username;

    /**
     * @JMS\Type("string")
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $password;

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername() : string
    {
        return $this->username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }
}