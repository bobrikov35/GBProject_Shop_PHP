<?php

namespace app\services;

use \PDO, \PDOStatement;
use app\entities\Entity;

/**
 * Class DB
 * @package app\services
 */
class DB extends Service
{
  private PDO $connect;

  /**
   * @param array $config
   */
  public function __construct( array $config = [] )
  {
    parent::__construct( $config );

    $this->makeConnect();
  }

  /**
   * Возвращает DSN-строку для PDO
   *
   * @return string
   */
  private function getPrepareDsn(): string
  {
    return sprintf(
      "%s:host=%s;dbname=%s;charset=%s;port=%d",
      $this->config['driver'],
      $this->config['host'],
      $this->config['dbname'],
      $this->config['charset'],
      $this->config['port'],
    );
  }

  /**
   * Соединение с базой данных
   */
  private function makeConnect(): void
  {
    $this->connect = new PDO(
      $this->getPrepareDsn(),
      $this->config['user'],
      $this->config['password']
    );

    $this->connect->setAttribute(
      PDO::ATTR_DEFAULT_FETCH_MODE,
      PDO::FETCH_ASSOC
    );
  }

  /**
   * Возвращает последний добавленный id
   *
   * @return int
   */
  public function getInsertedId(): int
  {
    return (int)$this->connect->lastInsertId();
  }

  /**
   * Выполняет подготовленный запрос и возвращает результат
   *
   * @param string $sql
   * @param array $params
   * @return PDOStatement|bool
   */
  private function query( string $sql, array $params = [] )
  {
    $PDOStatement = $this->connect->prepare( $sql );
    $PDOStatement->execute( $params );

    return $PDOStatement;
  }

  /**
   * Возвращает первую строку результата выполнения запроса
   *
   * @param string $sql
   * @param array $params
   * @return array
   */
  public function readItem( string $sql, array $params = [] ): array
  {
    if ( $result = $this->query( $sql, $params )->fetch() ) {
      return $result;
    }

    return [];
  }

  /**
   * Возвращает первую строку результата выполнения запроса в объект
   *
   * @param string $sql
   * @param string $class
   * @param array $params
   * @return Entity|null
   */
  public function readObject( string $sql, string $class, array $params = [] )
  {
    $PDOStatement = $this->query( $sql, $params );
    $PDOStatement->setFetchMode( PDO::FETCH_CLASS, $class );

    if ( $result = $PDOStatement->fetch() ) {
      return $result;
    }

    return null;
  }

  /**
   * Возвращает все строки результата выполнения запроса
   *
   * @param string $sql
   * @param array $params
   * @return array
   */
  public function readTable( string $sql, array $params = [] ): array
  {
    if ( $result = $this->query( $sql, $params )->fetchAll() ) {
      return $result;
    }

    return [];
  }

  /**
   * Возвращает все строки результата выполнения запроса в виде списка объектов
   *
   * @param string $sql
   * @param string $class
   * @param array $params
   * @return Entity[]|array
   */
  public function readObjectList( string $sql, string $class, array $params = [] ): array
  {
    $PDOStatement = $this->query( $sql, $params );
    $PDOStatement->setFetchMode( PDO::FETCH_CLASS, $class );

    if ( $result = $PDOStatement->fetchAll() ) {
      return $result;
    }

    return [];
  }

  /**
   * Выполняет запрос
   *
   * @param string $sql
   * @param array $params
   * @return PDOStatement|bool
   */
  public function execute( string $sql, array $params = [] )
  {
    if ( $this->query( $sql, $params ) ) {
      return true;
    }

    return false;
  }
}
