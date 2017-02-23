<?php

namespace Grimzy\JWTServiceProvider\JWT;

use Firebase\JWT;
use Grimzy\JWTServiceProvider\Exceptions\BeforeValidException;
use Grimzy\JWTServiceProvider\Exceptions\SignatureInvalidException;
use Grimzy\JWTServiceProvider\Exceptions\TokenExpiredException;

class JWTEncoder implements JWTEncoderInterface
{
    /**
     * @var array
     */
    private $options;

    private static $defaults = [
        'key' => null,
        'life_time' => null,
        'not_before' => null,
        'issued_at' => false,
        'leeway' => 0,
        'algorithm' => 'HS256',
        'accepted_algorithms' => ['HS256'],
        'payload' => null,
        'header' => null,
    ];

    public function __construct(array $options = [])
    {
        $this->options = array_merge(self::$defaults, $options);
    }

    /**
     * @inheritDoc
     */
    public function encode($payload, $header = null)
    {
        // We only merge if both are arrays
        if (!empty($this->options['payload']) && is_array($this->options['payload']) && is_array($payload)) {
            $payload = array_merge($this->options['payload'], $payload);
        }

        if (!empty($this->options['life_time']) && !isset($payload['exp'])) {
            $payload['exp'] = time() + $this->options['life_time'];
        }

        if (!empty($this->options['not_before']) && !isset($payload['nbf'])) {
            $payload['nbf'] = time() + $this->options['not_before'];
        }

        if (!empty($this->options['issued_at']) && !isset($payload['iat'])) {
            $payload['iat'] = time() + $this->options['issued_at'];
        }

        if (!empty($this->options['header'])) {
            $header = array_merge($this->options['header'], $header);
        }

        $key = $this->options['key'];
        $alg = $this->options['algorithm'];

        return JWT\JWT::encode($payload, $key, $alg, $header);
    }

    /**
     * @inheritDoc
     */
    public function decode($token)
    {
        JWT\JWT::$leeway = $this->options['leeway'];
        try {
            $decoded = JWT\JWT::decode($token, $this->options['key'], $this->options['accepted_algorithms']);
        } catch (JWT\ExpiredException $e) {
            throw new TokenExpiredException($e);
        } catch (JWT\BeforeValidException $e) {
            throw new BeforeValidException($e);
        } catch (JWT\SignatureInvalidException $e) {
            throw new SignatureInvalidException($e);
        }
        catch(\Exception $e) {
            throw $e;
        }
        JWT\JWT::$leeway = 0;

        return $decoded;
    }
}