<?php

namespace app\services;

use app\engine\{ App, Container };

/**
 * Class Service
 * @package app\services
 */
abstract class Service
{
  protected array $config;
  protected Container $container;
  protected Request $request;

  /**
   * @param array $config
   */
  public function __construct( array $config = [] )
  {
    $this->config = $config;
  }

  /**
   * @param Container $container
   */
  public function setContainer( Container $container ): void
  {
    $this->container = $container;
    $this->request = $container->request;
  }

  /**
   * @param string $name
   * @param mixed $value
   */
  protected function setSession( string $name, $value ): void
  {
    $this->request->setSession( $name, $value );
  }

  /**
   * @param string $key
   * @return mixed|null
   */
  protected function getSettings( string $key )
  {
    return App::call()->getSettings( $key );
  }

  /**
   * @param string $param
   * @return mixed|null
   */
  protected function getPost( string $param = '' )
  {
    return $this->request->getPost( $param );
  }

}
