<?php

namespace app\services;

use \Exception;
use \Twig\{ Environment, Loader\FilesystemLoader };

/**
 * Class RendererTemplate
 * @package app\services
 */
class RendererTemplate implements IRenderer
{
  protected Environment $twig;

  public function __construct()
  {
    $loader = new FilesystemLoader([
      VIEWS_DIR,
      COMPONENTS_DIR,
      LAYOUTS_DIR,
    ]);

    $this->twig = new Environment( $loader, [
      'cache' => 'compilation_cache',
      'auto_reload' => true,
    ]);
  }

  /**
   * Вывод шаблона
   *
   * @param string $template
   * @param array $params
   * @return string
   */
  public function render( string $template, array $params = [] ): string
  {
    try {
      return $this->twig->render( $template, $params );

    } catch ( Exception $exception ) {
      return $exception->getMessage();

    }
  }
}
