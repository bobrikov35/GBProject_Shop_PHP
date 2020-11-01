<?php

namespace app\controllers;

use app\engine\App;
use app\repositories\Repository;

/**
 * Class Controller
 * @package app\controllers
 */
abstract class Controller
{
  protected App $app;

  /**
   * @param App $app
   */
  public function __construct(App $app)
  {
    $this->app = $app;
  }

  /**
   * @return int
   */
  protected function getId(): int
  {
    return $this->app->request->getId();
  }

  /**
   * @return int
   */
  protected function getPage(): int
  {
    return $this->app->request->getPage();
  }

  /**
   * @param string $key
   * @return mixed|null
   */
  protected function getUser( string $key = '' )
  {
    return $this->app->request->getUser( $key );
  }

  /**
   * @param string $param
   * @return mixed|null
   */
  protected function getPost( string $param = '' )
  {
    return $this->app->request->getPost( $param );
  }

  /**
   * @param string $param
   * @return mixed|null
   */
  protected function getSession( string $param = '' )
  {
    return $this->app->request->getSession( $param );
  }

  /**
   * @return bool
   */
  public function isAdmin(): bool
  {
    return $this->app->request->isAdmin();
  }

  /**
   * @return bool
   */
  public function isLogin(): bool
  {
    return $this->app->request->isLogin();
  }

  /**
   * @param string $right
   * @return bool
   */
  public function permission( string $right ): bool
  {
    return $this->app->request->permission( $right );
  }

  /**
   * @param string $location
   * @param string $message
   */
  protected function redirect( string $location = '', string $message = '' ): void
  {
    $this->app->request->redirect( $location, $message );
  }

  /**
   * @param Repository $repository
   * @param string $path
   * @param int $page
   */
  protected function configurePaginator( Repository $repository, string $path, int $page = 1 ): void
  {
    $this->app->paginator->setPath( $path );
    $this->app->paginator->setItems( $repository, $page );
  }

  /**
   * @param string $key
   * @return mixed|null
   */
  protected function getSettings( string $key = '' )
  {
    return $this->app->getSettings( $key );
  }

  /**
   * @return array
   */
  protected function getCart(): array
  {
    return $this->app->serviceCart->getList();
  }

  /**
   * @param string $template
   * @param array $params
   * @return string
   */
  protected function render( string $template, array $params = [] ): string
  {
    return $this->app->renderer->render( $template, $params );
  }

  /**
   * Возвращает действие для текущего контроллера
   *
   * @return string
   */
  private function getAction(): string
  {
    $action = $this->app->request->getAction();
    $method = $action . "_action";

    if (! method_exists( $this, $method ) ) {
      $action = $this->getSettings( 'actionDefault' );
    }

    return $action;
  }

  /**
   * Возвращает общие конфигурации шаблонов
   *
   * @return array
   */
  protected function getConfig(): array
  {
    return [
      'time' => $this->getSettings( 'time' ),
      'admin' => $this->isAdmin(),
      'login' => $this->isLogin(),
      'cartCount' => count( $this->getCart() ),
      'user' => $this->getUser(),
    ];
  }

  /**
   * Выполняет действие текущего контроллера
   *
   * @return mixed
   */
  public function run()
  {
    $method = $this->getAction() . '_action';

    return $this->$method();
  }
}
