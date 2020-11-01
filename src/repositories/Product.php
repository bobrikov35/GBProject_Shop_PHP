<?php

namespace app\repositories;

use app\entities\{ Entity, Product as EProduct };


/**
 * Class Product
 * @package app\repositories
 */
class Product extends Repository
{
  /**
   * Возвращает название таблицы
   *
   * @return string
   */
  protected function getTableName(): string
  {
    return 'goods';
  }

  /**
   * Возвращает имя класса
   *
   * @return string
   */
  protected function getEntityName(): string
  {
    return EProduct::class;
  }

  /**
   * Получает все изображения товара
   *
   * @param Entity|EProduct $product
   * @return void
   */
  public function fetchImages( EProduct $product ): void
  {
    $sql = 'SELECT link FROM images WHERE id_product = :id';

    $product->setImages( $this->readTable( $sql, [ ':id' => $product->getId() ] ) );
  }

  /**
   * Получает все отзывы о товаре
   *
   * @param Entity|EProduct $product
   * @return void
   */
  public function fetchFeedbacks( EProduct $product ): void
  {
    $sql = <<<QUERY
      SELECT firstname, lastname, email, body
        FROM feedbacks AS f
       INNER JOIN users AS u ON u.id = f.id_user
       WHERE id_product = :id
QUERY;

    $product->setFeedbacks( $this->readTable( $sql, [ ':id' => $product->getId() ] ) );
  }

  /**
   * Возвращает товар по id
   *
   * @param int $id
   * @return Entity|EProduct|null
   */
  public function getSingle( int $id )
  {
    $product = parent::getSingle( $id );

    if ( empty( $product ) ) {
      return null;
    }

    $this->fetchImages( $product );
    $this->fetchFeedbacks( $product );

    return $product;
  }

  /**
   * Производит добавление товара
   *
   * @param Entity|EProduct $product
   * @return int
   */
  protected function insert( Entity $product ): int
  {
    if ( empty( $id = parent::insert( $product ) ) ) {
      return $id;
    }

    foreach ( $product->getImages() as $image ) {
      if ( $image == '' ) {
        continue;
      }

      $sql = 'INSERT INTO images (link, id_product) VALUES ( :link, :id )';
      $this->execute( $sql, [ ':link' => $image, ':id' => $id ] );
    }

    return $id;
  }

  /**
   * Производит изменение товара
   *
   * @param Entity|EProduct $product
   * @return bool
   */
  protected function update( Entity $product ): bool
  {
    if (! parent::update( $product ) ) {
      return false;
    }

    $newImages = array_unique( $product->getImages() );
    $this->fetchImages( $product );

    foreach ( $product->getImages() as $image ) {
      if ( in_array( $image, $newImages, true ) ) {
        continue;
      }

      $sql = 'DELETE FROM images WHERE id_product = :id and link = :link';
      $this->execute( $sql, [ ':id' => $product->getId(), ':link' => $image ] );
    }

    foreach ( $newImages as $image ) {
      if ( in_array( $image, $product->getImages(), true ) ) {
        continue;
      }

      $sql = 'INSERT INTO images (link, id_product) VALUES (:link, :id)';
      $this->execute( $sql, [ ':id' => $product->getId(), ':link' => $image ] );
    }

    return true;
  }
}
