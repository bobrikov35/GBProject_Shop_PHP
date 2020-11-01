<?php

namespace app\controllers;

use app\entities\User as EUser;

/**
 * Class User
 * @package app\controllers
 */
class User extends Controller
{
  /**
   * @return EUser
   */
  private function getUserFromPost(): EUser
  {
    return $this->app->serviceUser->getUserFromPost();
  }

  /**
   * @param EUser $user
   * @return bool
   */
  private function login( EUser $user ): bool
  {
    return $this->app->authorization->login( $user->getEmail(), $user->getPassword() );
  }

  private function logout(): void
  {
    $this->app->authorization->logout();
  }

  /**
   * @return bool|int
   */
  private function saveUser()
  {
    return $this->app->serviceUser->save();
  }

  /**
   * @return bool
   */
  private function deleteUser(): bool
  {
    return $this->app->serviceUser->delete( $this->getUser( 'id' ) );
  }

  /**
   * Действие по умолчанию
   */
  protected function default_action()
  {
    if (! $this->isLogin() ) {
      $this->redirect( '/user/login/' );
      return;
    }
    $this->redirect( '/user/account/' );
  }

  /**
   * Выводит личный кабинет
   *
   * @return string|void
   */
  protected function account_action()
  {
    if (! $this->isLogin() ) {
      $this->redirect( '/user/login/' );
      return;
    }

    return $this->render( 'user/index.twig', $this->getConfig() );
  }

  /**
   * Выводит личный кабинет при соответствии логина и пароля или дает возможность произвести вход повторно
   *
   * @return string|void
   */
  protected function login_action()
  {
    if ( $this->isLogin() ) {
      $this->redirect( '/user/account/' );
      return;
    }

    $config = $this->getConfig();

    if ( empty( $this->getPost() ) ) {
      $config[ 'user' ] = new EUser();
      return $this->render( 'user/login.twig', $config );
    }

    $config[ 'user' ] = $this->getUserFromPost();

    if ( empty( $config[ 'user' ]->getEmail() ) ) {
      return $this->render( 'user/login.twig', $config );
    }

    if ( $this->login( $config[ 'user' ] ) ) {
      $this->redirect( '/user/account' );
      return;
    }

    $config[ 'message' ] = 'Вы ввели неверное имя пользователя или неверный пароль';
    return $this->render( '/user/login.twig', $config );
  }

  /**
   * Выполняет выход из личного кабинета
   */
  protected function logout_action()
  {
    $this->logout();
    $this->redirect();
  }

  /**
   * Проверка заполненности обязательных параметров
   *
   * @return bool
   */
  private function checkRequiredParams(): bool
  {
    return !empty( $this->getPost( 'firstname' ) ) and !empty( $this->getPost( 'email' ) );
  }

  /**
   * Возвращает страницу создания личного кабинета или создает его
   *
   * @return string|void
   */
  protected function create_action()
  {
    $config = $this->getConfig();

    if ( empty( $this->getPost() ) ) {
      $config[ 'user' ] = new EUser();
      return $this->render( 'user/create.twig', $config );
    }

    if ( $this->getPost( 'password' ) !== $this->getPost( 'password-check' ) ) {
      $config[ 'message' ] = 'Введенные Вами пароли не совпадают';
      $config[ 'user' ] = $this->getUserFromPost();

      return $this->render('user/create.twig', $config);
    }

    if ( $this->checkRequiredParams() and $this->saveUser() ) {
      $this->redirect( '/user/account' );
      return;
    }

    $config[ 'message' ] = 'Вы заполнили не все обязательные поля';
    $config[ 'user' ] = $this->getUserFromPost();

    return $this->render('user/create.twig', $config);
  }

  /**
   * Удаляет личный кабинет
   */
  protected function delete_action(): void
  {
    if (! $this->isLogin() ) {
      $this->redirect( '/user/login' );
      return;
    }

    if ( $this->deleteUser() ) {
      $this->logout();
      $this->redirect( '/user/login' );
      return;
    }

    $this->redirect( '/user/account' );
  }

  /**
   * Помощник ide (не вызывать)
   */
  protected function __ideHelper(): void
  {
    /** Функции вызываются динамически (см. class Controller) */
    $this->default_action();
    $this->account_action();
    $this->login_action();
    $this->logout_action();
    $this->create_action();
    $this->delete_action();
  }
}
