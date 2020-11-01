<?php

namespace app\services;

use app\engine\Container;

/**
 * Class Request
 * @package app\services
 */
class Request extends Service
{
  private array $params;
  private string $controller = '';
  private string $action = '';
  private int $id = 0;
  private int $page = 1;

  /**
   * @param array $config
   */
  public function __construct( array $config = [] )
  {
    parent::__construct( $config );

    session_start();
    $this->params = [
      'get' => !empty( $_GET ) ? $_GET : [],
      'post' => !empty( $_POST ) ? $_POST : [],
      'session' => !empty( $_SESSION ) ? $_SESSION : [],
    ];

    $this->parseRequest();
  }

  /**
   * Обрабатывает запрос
   */
  private function parseRequest(): void
  {
    $uri = $_SERVER[ 'REQUEST_URI' ];
    $pattern = '#(?P<controller>\w+)[/]?(?P<action>\w+)?[/]?[?]?(?P<params>.*)#ui';

    if ( preg_match_all( $pattern , $uri, $matches)) {
      $this->controller = ucfirst(strtolower($matches['controller'][0]));
      $this->action = strtolower($matches['action'][0]);
    }

    if ( is_numeric( $this->getParams( 'get', 'id' ) ) ) {
      $this->id = (int)$this->getParams( 'get', 'id' );
    }

    if ( is_numeric( $this->getParams( 'get', 'page' ) ) ) {
      $this->page = (int)$this->getParams( 'get', 'page' );
    }
  }

  /**
   * @param Container $container
   */
  public function setContainer( Container $container ): void
  {
    $this->container = $container;
  }

  /**
   * @return string
   */
  public function getAction(): string
  {
    return $this->action;
  }

  /**
   * @return string
   */
  public function getController(): string
  {
    return "app\\controllers\\{$this->controller}";
  }

  /**
   * @return int
   */
  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @return int
   */
  public function getPage(): int
  {
    return $this->page;
  }

  /**
   * Записывает данные в сессию
   *
   * @param string $name
   * @param mixed $value
   */
  public function setSession( string $name, $value ): void
  {
    $_SESSION[ $name ] = $value;
  }

  /**
   * Возвращает данные текущего пользователя
   *
   * @param string $key
   * @return mixed|null
   */
  public function getUser( string $key = '' )
  {
    $user = $this->getSession('user');

    if ( empty( $user ) ) {
      return null;
    }

    if ( empty( $key ) ) {
      return $user;
    }

    if ( empty ( $user[ $key ] ) ) {
      return null;
    }

    return $user[ $key ];
  }

  /**
   * Возвращает массив (get, post или session) или параметр из массива
   *
   * @param string $list
   * @param string $param
   * @return mixed|null
   */
  public function getParams( string $list = 'get', string $param = '' )
  {
    if (! in_array( $list, [ 'get', 'post', 'session' ] ) ) {
      return null;
    }

    if ( empty( $param ) ) {
      return $this->params[ $list ];
    }

    if ( empty( $this->params[ $list ][ $param ] ) ) {
      return null;
    }

    return $this->params[ $list ][ $param ];
  }

  /**
   * Возвращает post-массив или параметр из массива
   *
   * @param string $param
   * @return mixed|null
   */
  public function getPost( string $param = '' )
  {
    return $this->getParams( 'post', $param );
  }

  /**
   * Возвращает session-массив или параметр из массива
   *
   * @param string $param
   * @return mixed|null
   */
  public function getSession( string $param = '' )
  {
    return $this->getParams( 'session', $param );
  }

  /**
   * Проверка на наличие прав администратора
   *
   * @return bool
   */
  public function isAdmin(): bool
  {
    $user = $this->getSession( 'user' );

    return !empty( $user ) and $user[ 'admin' ];
  }

  /**
   * Проверка на авторизацию
   *
   * @return bool
   */
  public function isLogin(): bool
  {
    $user = $this->getSession( 'user' );

    return !empty( $user );
  }

  /**
   * Проверка на наличие прав
   *
   * @param string $rights
   * @return bool
   */
  public function permission( string $rights )
  {
    switch ( $rights ) {
      case 'admin':
        return $this->isAdmin();

      case 'user':
        return $this->isLogin();

      case 'guest':
      default:
        return !$this->isLogin();
    }
  }

  /**
   * Выполняет перенаправление
   *
   * @param string $location
   * @param string $message
   */
  public function redirect( string $location = '', string $message = '' ): void
  {
    if (! empty( $location ) ) {
      header( "location: {$location}" );

    } elseif ( empty( $_SERVER[ 'HTTP_REFERER' ] ) ) {
      header( 'location: /' );

    } else {
      header( "location: {$_SERVER['HTTP_REFERER']}" );
    }
  }
}
