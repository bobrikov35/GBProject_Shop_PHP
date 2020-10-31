<?php

namespace app\engine;

// use app\controllers\{Cart as CCart, Order as COrder, Product as CProduct};
// use app\repositories\{Order as ROrder};
use app\services\{ DB, Paginator, RendererTemplate, Request };
use app\controllers\{ Home };
use app\repositories\{ User as RUser, Product as RProduct };
use app\services\{ Authorization, Cart as SCart, Product as SProduct, User as SUser };
use app\traits\TSingleton;

/**
 * Class App
 * @package app\engine
 *
 * @property DB $database
 * @property RendererTemplate $renderer
 * @property Request $request
 * @property Authorization $authorization
 * @property Paginator $paginator
 *
 * @property CCart $controllerCart
 * @property Home $controllerHome
 * @property COrder $controllerOrder
 * @property CProduct $controllerProduct
 *
 * @property ROrder $repositoryOrder
 * @property RProduct $repositoryProduct
 * @property RUser $repositoryUser
 *
 * @property SCart $serviceCart
 * @property SProduct $serviceProduct
 * @property SUser $serviceUser
 */
class App
{
  private array $config = [];
  private Container $container;

  use TSingleton;

  /**
   * @param string $name
   * @return mixed|null
   */
  public function __get( string $name )
  {
    return $this->container->$name;
  }

  /**
   * Возвращает экземпляр приложения
   *
   * @return App
   */
  public static function call()
  {
    return static::getInstance();
  }

  /**
   * Возвращает настройки приложения
   *
   * @param string $key
   * @param string|null $defaultValue
   * @return mixed|null
   */
  public function getSettings( string $key = '', $defaultValue = null )
  {
    if ( empty( $key ) ) {
      return $this->config;
    }

    if ( !empty( $this->config[ $key ] ) ) {
      return $this->config[ $key ];
    }

    return $defaultValue;
  }

  /**
   * Устанавливает контейнер с классами
   */
  private function setContainer(): void
  {
    $this->container = new Container(
      $this->config[ 'components' ]
    );
  }

  /**
   * Запускает приложение
   *
   * @param array $config
   * @return mixed
   */
  public function run(array $config)
  {
    $this->config = $config;
    $this->setContainer();

    return $this->runController();
  }

  /**
   * Запускает контроллер
   *
   * @return mixed
   */
  private function runController()
  {
    $controllerName = $this->request->getController();

    if ( !class_exists( $controllerName ) ) {
      $controllerName = "app\\controllers\\{$this->config[ 'controllerDefault' ]}";
    }

    $controller = new $controllerName( $this );

    return $controller->run( $this->request->getAction() );
  }
}
