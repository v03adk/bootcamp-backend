<?php

namespace AppBundle\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        // Overrides
        $docs['securityDefinitions'] = [
            'apikey' => [
                'type' => 'apiKey',
                'name' => 'apikey',
                'in' => 'header'
            ]
        ];
        $docs['security'] = ['apikey' => []];

        $docs['info']['title'] = 'Random Quote API';

        $info = clone $docs['paths']['/api/quotes/{id}']['get'];
        $info['summary'] = 'Retrieves a random Quote resource.';
        $info['parameters'] = [];
        $docs['paths']['/api/quotes/random'] = [

                'get' => $info

        ];

        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
