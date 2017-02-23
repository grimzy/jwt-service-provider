<?php
namespace Grimzy\JWTServiceProvider;

use Grimzy\JWTServiceProvider\JWT\JWTEncoder;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class JWTServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container)
    {
        $container['jwt.encoder.factory'] = $container->protect(
            function ($name = null, array $options = []) use ($container) {
                $encoder_config = empty($name) || !isset($container['jwt.encoders'][$name]) ? [] : $container['jwt.encoders'][$name];

                if (is_array($encoder_config)) {
                    $options = array_merge($encoder_config, $options);
                }

                $encoder = new JWTEncoder($options);

                // TODO: save the encoder as a service for later reuse?

                return $encoder;
            }
        );
    }
}