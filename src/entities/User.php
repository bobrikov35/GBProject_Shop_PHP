<?php

namespace app\entities;

/**
 * Class User
 * @package app\entities
 */
class User extends Entity
{
  private string $firstname = '';
  private string $lastname = '';
  private string $email = '';
  private string $password = '';
  private bool $admin = false;

  /**
   * @return array
   */
  public function getVars(): array
  {
    $vars = get_object_vars( $this );
    unset( $vars[ 'id' ] );

    return $vars;
  }

  /**
   * @param mixed $name
   */
  public function setFirstname( $name ): void
  {
    $this->firstname = !empty( $name ) ? (string)$name : '';
  }

  /**
   * @param mixed $name
   */
  public function setLastname( $name ): void
  {
    $this->lastname = !empty( $name ) ? (string)$name : '';
  }

  /**
   * @param mixed $email
   */
  public function setEmail( $email ): void
  {
    $this->email = !empty( $email ) ? (string)$email : '';
  }

  /**
   * @param mixed $password
   */
  public function setPassword( $password ): void
  {
    $this->password = !empty( $password ) ? (string)$password : '';
  }

  /**
   * @param mixed $admin
   */
  public function setAdmin( $admin ): void
  {
    $this->admin = !empty( $admin ) and $admin;
  }

  /**
   * @return string
   */
  public function getFirstname(): string
  {
    return $this->firstname;
  }

  /**
   * @return string
   */
  public function getLastname(): string
  {
    return $this->lastname;
  }

  /**
   * @return string
   */
  public function getEmail(): string
  {
    return $this->email;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
    return $this->password;
  }

  /**
   * @return bool
   */
  public function getAdmin(): bool
  {
    return $this->admin;
  }

}
