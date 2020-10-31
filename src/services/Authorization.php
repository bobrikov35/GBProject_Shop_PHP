<?php

namespace app\services;

use app\entities\User as EUser;

/**
 * Class Authorization
 * @package app\services
 */
class Authorization extends Service
{
  /**
   * @param string $email
   * @return string|bool
   */
  private function getPassword( string $email )
  {
    return $this->container->repositoryUser->getPassword( $email );
  }

  /**
   * @param string $email
   * @return EUser|bool
   */
  private function getUser( string $email )
  {
    return $this->container->repositoryUser->getUser( $email );
  }

  /**
   * Проверка пароля
   *
   * @param string $email
   * @param string $password
   * @return bool
   */
  private function verifyPassword(string $email, string $password): bool
  {
    $password = $this->getSettings( 'passwordSol' ) . $password;

    if ( empty( $email ) ) {
      return false;
    }

    if ( empty( $hashPassword = $this->getPassword( $email ) ) ) {
      return false;
    }

    return password_verify( $password, $hashPassword );
  }

  /**
   * Вход в аккаунт
   *
   * @param string $email
   * @param string $password
   * @return bool
   */
  public function login(string $email, string $password): bool
  {
    if ( !( $this->verifyPassword( $email, $password ) ) ) {
      $this->logout();
      return false;
    }

    $user = $this->getUser( $email );

    if ( !$user ) {
      $this->logout();
      return false;
    }

    $this->setSession( 'user', [
      'id' => $user->getId(),
      'firstname' => $user->getFirstname(),
      'lastname' => $user->getLastname(),
      'email' => $user->getEmail(),
      'admin' => $user->getAdmin(),
    ]);

    return true;
  }

  /**
   * Выход из аккаунта
   */
  public function logout(): void
  {
    $this->setSession( 'user', [] );
  }
}
