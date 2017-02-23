<?php
namespace Grimzy\JWTServiceProvider\JWT;

interface JWTEncoderInterface
{
    /**
     * Encode a payload into a JWT string
     *
     * @param mixed $payload PHP object or array
     * @param array $header An array with header elements to attach
     *
     * @return string A signed JWT token
     */
    public function encode($payload, $header = null);

    /**
     * Decodes a JWT string into a string or object
     *
     * @param string $token The JWT token
     *
     * @return string|object The payload
     */
    public function decode($token);
}