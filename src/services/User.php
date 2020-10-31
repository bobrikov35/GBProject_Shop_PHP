<?php

namespace app\services;

use app\entities\{ Entity, User as EUser };

/**
 * Class User
 * @package app\services
 */
class User extends Service
{
  /**
   * @param int $id
   * @return Entity|EUser|null
   */
  private function getUser( int $id )
  {
    return $this->container->repositoryUser->getSingle($id);
  }

  /**
   * @param string $email
   * @return Entity|EUser|null
   */
  private function getUserByEmail( string $email )
  {
    return $this->container->repositoryUser->getUser( $email );
  }

  /**
   * @param EUser $user
   * @return bool|int
   */
  private function saveUser( EUser $user )
  {
    return $this->container->repositoryUser->save( $user );
  }

  /**
   * @param int $id
   * @return bool
   */
  private function deleteUser( int $id ): bool
  {
    return $this->container->repositoryUser->delete( $id );
  }

  /**
   * Заполняет данные пользователя из POST
   *
   * @param EUser $user
   */
  private function fillUserFromPost(EUser $user): void
  {
    $user->setFirstname( $this->getPost( 'name' ) );
    $user->setLastname( $this->getPost( 'name' ) );
    $user->setEmail( $this->getPost( 'email' ) );
    $user->setPassword( $this->getPost( 'password' ) );
  }

  /**
   * Возвращает пользователя с данными из POST
   *
   * @return EUser
   */
  public function getUserFromPost()
  {
    $user = new EUser();
    $this->fillUserFromPost($user);
    return $user;
  }

  /**
   * Сохраняет данные пользователя
   *
   * @return bool|int
   */
  public function save()
  {
    $user = $this->getUserFromPost();
    $userEmail = $this->getUserByEmail( $user->getEmail() );

    if ( !empty( $userEmail ) ) {
      return 0;
    }

    $user->setPassword( $this->getPasswordHash( $user->getPassword() ) );
    $user->setId( (int)$this->saveUser( $user ) );

    if ( empty( $user->getId() ) ) {
      return 0;
    }

    $this->setSession( 'user', [
      'id' => $user->getId(),
      'firstname' => $user->getFirstname(),
      'lastname' => $user->getLastname(),
      'email' => $user->getEmail(),
      'admin' => $user->getAdmin(),
    ]);

    return $user->getId();
  }

  /**
   * Удаляет пользователя по идентификатору
   *
   * @param int $id
   * @return bool
   */
  public function delete( int $id )
  {
    if ( empty( $id ) ) {
      return false;
    }

    if ( $this->getUser( $id ) ) {
      return $this->deleteUser( $id );
    }

    return false;
  }

  /**
   * Возвращает хешированный пароль
   *
   * @param string $password
   * @return string
   */
  private function getPasswordHash(string $password): string
  {
    $password = $this->getSettings( 'passwordSol' ) . $password;
    return password_hash( $password, PASSWORD_DEFAULT );
  }
}
