<?php

namespace app\entities;

/**
 * Class Entity
 * @package app\entities
 */
abstract class Entity
{
  protected int $id = 0;

  /**
   * В классе наследнике должна возвращать свойства класса
   *
   * @return array
   */
  abstract public function getVars(): array;

  /**
   * @param mixed $id
   */
  public function setId( $id ): void
  {
    $this->id = is_numeric( $id ) ? (int)$id : 0;
  }

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }
}
