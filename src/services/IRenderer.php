<?php

namespace app\services;

/**
 * Interface IRenderer
 * @package app\services
 */
interface IRenderer
{
  /**
   * Вывод шаблона
   *
   * @param string $template
   * @param array $params
   * @return mixed
   */
  public function render( string $template, array $params = [] );
}
