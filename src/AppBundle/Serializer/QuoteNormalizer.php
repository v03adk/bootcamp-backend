<?php

namespace AppBundle\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use ApiPlatform\Core\Serializer\ItemNormalizer;
use AppBundle\Entity\Quote;

/**
 * Class QuoteNormalizer
 */
class QuoteNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @var ItemNormalizer
     */
    private $normalizer;

    /**
     * QuoteNormalizer constructor.
     * @param ItemNormalizer $normalizer
     */
    public function __construct(ItemNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * @param mixed $object
     * @param null  $format
     * @param array $context
     * @return array|bool|float|int|string
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $this->normalizer->normalize($object, $format, $context);
    }

    /**
     * @param mixed $data
     * @param null  $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $this->normalizer->supportsNormalization($data, $format) && $data instanceof Quote;
    }

    /**
     * @param mixed  $data
     * @param string $class
     * @param null   $format
     * @param array  $context
     * @return object
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        if (isset($data['author'])) {
            $data['author'] = ['id' => $data['author']];
        }

        return $this->normalizer->denormalize($data, $class, $format, $context);
    }

    /**
     * @param mixed  $data
     * @param string $type
     * @param null   $format
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $this->normalizer->supportsDenormalization($data, $type, $format) && $type === Quote::class;
    }
}
