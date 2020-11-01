<?php

namespace app\repositories;

use app\engine\Container;
use app\services\DB;
use app\entities\Entity;

/**
 * Class Repository
 * @package app\repositories
 */
abstract class Repository
{
  protected Container $container;

  /**
   * В классе наследнике должна возвращать название таблицы
   *
   * @return string
   */
  abstract protected function getTableName(): string;

  /**
   * В классе наследнике должна возвращать имя класса
   *
   * @return string
   */
  abstract protected function getEntityName(): string;

  /**
   * @param Container $container
   */
  public function setContainer( Container $container ): void
  {
    $this->container = $container;
  }

  /**
   * @return DB
   */
  protected function getDatabase(): DB
  {
    return $this->container->database;
  }

  /**
   * @param string $param
   * @return mixed|null
   */
  protected function getPost( string $param )
  {
    return $this->container->request->getPost( $param );
  }

  /**
   * @param string $param
   * @return mixed|null
   */
  protected function getSession( string $param )
  {
    return $this->container->request->getSession( $param );
  }

  /**
   * @param string $sql
   * @param array $params
   * @return array
   */
  protected function readItem( string $sql, array $params = [] ): array
  {
    return $this->getDatabase()->readItem( $sql, $params );
  }

  /**
   * @param string $sql
   * @param string $class
   * @param array $params
   * @return Entity|null
   */
  protected function readObject( string $sql, string $class, array $params = [] )
  {
    return $this->getDatabase()->readObject( $sql, $class, $params );
  }

  /**
   * @param string $sql
   * @param array $params
   * @return array
   */
  protected function readTable( string $sql, array $params = [] ): array
  {
    return $this->getDatabase()->readTable( $sql, $params );
  }

  /**
   * @param string $sql
   * @param string $class
   * @param array $params
   * @return array
   */
  protected function readObjectList( string $sql, string $class, array $params = [] ): array
  {
    return $this->getDatabase()->readObjectList( $sql, $class, $params );
  }

  /**
   * @param string $sql
   * @param array $params
   * @return bool|\PDOStatement
   */
  protected function execute( string $sql, array $params = [] )
  {
    return $this->getDatabase()->execute( $sql, $params );
  }

  /**
   * Возвращает количество записей в таблице
   *
   * @return int
   */
  public function getQuantityItems(): int
  {
    $sql = "SELECT COUNT(*) AS count FROM {$this->getTableName()}";

    if ( $result = $this->readItem( $sql ) ) {
      return (int)$result[ 'count' ];
    }

    return 0;
  }

  /**
   * Возвращает список объектов на текущей странице
   *
   * @param int $page
   * @param int $quantity
   * @return Entity[]|array
   */
  public function getItemsByPage( int $page = 1, int $quantity = 9 ): array
  {
    if ( $page < 1 ) {
      $page = 1;
    }

    if ( $quantity < 9 ) {
      $quantity = 9;
    }

    $start = ( $page - 1 ) * $quantity;
    $sql = "SELECT * FROM {$this->getTableName()} LIMIT {$start}, {$quantity}";

    return $this->readObjectList($sql, $this->getEntityName());
  }

  /**
   * Возвращает объект по id
   *
   * @param int $id
   * @return Entity|null
   */
  public function getSingle(int $id)
  {
    $sql = "SELECT * FROM {$this->getTableName()} WHERE id = :id";

    return $this->readObject( $sql, $this->getEntityName(), [ ':id' => $id ] );
  }

  /**
   * Возвращает полный список объектов
   *
   * @return array
   */
  public function getList(): array
  {
    $sql = "SELECT * FROM {$this->getTableName()}";

    return $this->readObjectList( $sql, $this->getEntityName() );
  }

  /**
   * Сохраняет объект
   *
   * @param Entity $entity
   * @return bool|int
   */
  public function save( Entity $entity )
  {
    if ( empty( $entity->getId() ) ) {
      return $this->insert( $entity );
    }
    return $this->update( $entity );
  }

  /**
   * Удаляет объект
   *
   * @param int $id
   * @return bool
   */
  public function delete( int $id ): bool
  {
    $sql = "DELETE FROM {$this->getTableName()} WHERE id = :id";

    if ( $this->execute( $sql, [ ':id' => $id ] ) ) {
      return true;
    }

    return false;
  }

  /**
   * Возвращает спискок параметров для запросов на создание и изменение
   *
   * @param array $vars
   * @return array
   */
  protected function getParams( array $vars ): array
  {
    $params = [
      'keys' => [],
      'values' => [],
      'columns' => [],
    ];

    foreach ( $vars as $key => $value ) {
      if ( empty( $value ) ) {
        continue;
      }

      $params[ 'columns' ][] = $key;
      $params[ 'keys' ][] = ":{$key}";
      $params[ 'values' ][ ":{$key}" ] = $value;
    }

    return $params;
  }

  /**
   * Возвращает подготовленный набор column=key для запросов на изменение
   *
   * @param array $columns
   * @param array $keys
   * @return array
   */
  private function getSetForUpdate( array $columns, array $keys ): array
  {
    $set = [];

    if ( count( $columns ) != count( $keys ) ) {
      return $set;
    }

    for ( $i = 0; $i < count( $columns ); $i++ ) {
      $set[] = "`{$columns[$i]}` = {$keys[$i]}";
    }

    return $set;
  }

  /**
   * Добавляет объект
   *
   * @param Entity $entity
   * @return int
   */
  protected function insert( Entity $entity ): int
  {
    $params = $this->getParams( $entity->getVars() );

    $sql = sprintf(
      "INSERT INTO %s (%s) VALUES (%s)",
      $this->getTableName(),
      implode( ', ', $params['columns']),
      implode( ', ', $params['keys'])
    );

    if ( $this->execute( $sql, $params['values'] ) ) {
      return $this->getDatabase()->getInsertedId();
    }

    return 0;
  }

  /**
   * Изменяет объект
   *
   * @param Entity $entity
   * @return bool
   */
  protected function update( Entity $entity ): bool
  {
    $params = $this->getParams( $entity->getVars() );

    $sql = sprintf(
      /** @lang text */ "UPDATE %s SET %s WHERE id = :id",
      $this->getTableName(),
      implode( ', ', $this->getSetForUpdate( $params[ 'columns' ], $params[ 'keys' ] ) )
    );

    $params[ 'values' ][ 'id' ] = $entity->getId();

    if ( $this->execute( $sql, $params[ 'values' ] ) ) {
      return true;
    }

    return false;
  }
}
