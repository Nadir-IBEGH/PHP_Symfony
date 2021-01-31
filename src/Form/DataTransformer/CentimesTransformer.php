<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class CentimesTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return float|int|mixed|void
     */
    public function transform($value)
    {
        if ($value === null) {
            return;
        }
        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return float|int|mixed|void
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }
        return $value * 100;
    }
}