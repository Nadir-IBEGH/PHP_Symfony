<?php
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class PriceTye extends AbstractType
{
  public function getParent()
  {
      return NumberType::class;
  }
}