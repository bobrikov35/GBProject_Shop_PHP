<?php

namespace app\services;

use app\entities\{ Product as EProduct };

/**
 * Class Product
 * @package app\services
 */
class Product extends Service
{
  /**
   * @param EProduct $product
   * @return bool|int
   */
  private function saveProduct( EProduct $product )
  {
    return $this->container->repositoryProduct->save( $product );
  }

  /**
   * @param int $id
   * @return bool
   */
  private function deleteProduct( int $id ): bool
  {
    return $this->container->repositoryProduct->delete( $id );
  }

  /**
   * Возвращает товар по id
   *
   * @param int $id
   * @return EProduct|null
   */
  public function getProduct( int $id )
  {
    return $this->container->repositoryProduct->getSingle( $id );
  }

  /**
   * Заполняет товар данными из post-запроса
   *
   * @param EProduct $product
   */
  private function fillProductFromPost( EProduct $product ): void
  {
    $product->setName( $this->getPost( 'name' ) );
    $product->setTitle( $this->getPost( 'title' ) );
    $product->setDescription( $this->getPost( 'description' ) );
    $product->setImage( $this->getPost( 'image' ) );
    $product->setPrice( $this->getPost( 'price' ) );
    $product->setImagesFromString( $this->getPost( 'images' ) );
  }

  /**
   * Возвращает товар из POST-запроса
   *
   * @return EProduct
   */
  public function getProductFromPost()
  {
    $product = new EProduct();
    $this->fillProductFromPost( $product );

    return $product;
  }

  /**
   * Производит сохранение товара в базе
   *
   * @param int $id
   * @return bool|int
   */
  public function save( int $id )
  {
    if ( empty( $id ) ) {
      $product = new EProduct();

    } else {
      $product = $this->getProduct( $id );
    }

    $this->fillProductFromPost( $product );

    return $this->saveProduct( $product );
  }

  /**
   * Производит удаление товара из базы
   *
   * @param int $id
   * @return bool
   */
  public function delete( int $id ): bool
  {
    if ( empty( $id ) ) {
      return false;
    }

    if ( $this->getProduct( $id ) ) {
      return $this->deleteProduct( $id );
    }

    return false;
  }
}
