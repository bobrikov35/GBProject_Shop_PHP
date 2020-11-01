<?php

namespace app\traits;

trait TSingleton
{
  private static $items;

  private function __construct() {}
  private function __clone() {}
  private function __wakeup() {}

  /**
   * Возвращает экземпляр класса в единственном экземпляре
   *
   * @return mixed
   */
  public static function getInstance()
  {
    if (empty(static::$items)) {
      static::$items = new static();
    }

    return static::$items;
  }

  /**
   * Помощник ide (не вызывать)
   */
  protected function __ideHelper(): void
  {
    $this->__wakeup();
  }
}
