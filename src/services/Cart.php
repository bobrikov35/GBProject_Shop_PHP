<?php

namespace app\services;

use app\entities\Product as EProduct;

/**
 * Class Cart
 * @package app\services
 */
class Cart extends Service
{
  /**
   * @param int $id
   * @return EProduct|bool
   */
  private function getSingleProduct( int $id )
  {
    return $this->container->repositoryProduct->getSingle( $id );
  }

  /**
   * @param string $param
   * @return mixed|null
   */
  private function getParams( string $param )
  {
    return $this->request->getParams( 'get', $param );
  }

  /**
   * @return mixed|null
   */
  private function getCart()
  {
    return $this->request->getSession('cart');
  }

  /**
   * Возвращает содержимое корзины
   *
   * @return array
   */
  public function getList(): array
  {
    if ( !empty( $cart = $this->getCart() ) ) {
      return $cart;
    }

    return [];
  }

  /**
   * Возвращает количество товара в корзине (для добавления или удаления)
   *
   * @return int
   */
  private function getQuantity(): int
  {
    $quantity = $this->getParams('quantity');

    if ( is_numeric($quantity) and (int)$quantity > 0 ) {
      return (int)$quantity;
    }

    return 1;
  }

  /**
   * Добавляет товар в корзину
   *
   * @param int $id_product
   * @return bool
   */
  public function add( int $id_product ): bool
  {
    if ( empty( $id_product ) ) {
      return false;
    }

    $cart = $this->getList();

    if ( key_exists( $id_product, $cart ) ) {
      $cart[ $id_product ][ 'quantity' ] += $this->getQuantity();
      $this->setSession( 'cart', $cart );
      return true;
    }

    if ( empty( $product = $this->getSingleProduct( $id_product ) ) ) {
      $this->setSession( 'cart', $cart );
      return false;
    }

    $cart[ $id_product ] = [
      'time' => time(),
      'title' => $product->getTitle(),
      'image' => $product->getImage(),
      'price' => $product->getPrice(),
      'quantity' => $this->getQuantity(),
    ];

    $this->setSession( 'cart', $cart );

    return true;
  }

  /**
   * Убирает товар из корзины
   *
   * @param int $id_product
   * @param bool $all
   * @return bool
   */
  public function remove( int $id_product, bool $all = false ): bool
  {
    if ( empty( $id_product ) ) {
      return false;
    }

    if ( empty( $cart = $this->getCart() ) ) {
      $this->clear();
      return false;
    }

    if ( !key_exists( $id_product, $cart ) ) {
      return false;
    }

    if ( $all or $cart[ $id_product ][ 'quantity' ] <= $this->getQuantity() ) {
      unset( $cart[ $id_product ] );

    } else {
      $cart[ $id_product ][ 'quantity' ] -= $this->getQuantity();
    }

    $this->setSession( 'cart', $cart );

    return true;
  }

  /**
   * Удаляет товар из корзины
   *
   * @param int $id_product
   * @return bool
   */
  public function delete( int $id_product ): bool
  {
    return $this->remove( $id_product, true );
  }

  /**
   * Очищает корзину
   */
  public function clear(): void
  {
    $this->setSession('cart', []);
  }
}
