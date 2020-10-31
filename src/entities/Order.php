<?php

namespace app\entities;

/**
 * Class Order
 * @package app\entities
 */
class Order extends Entity
{
  private int $userId = 0;
  private string $status = 'Передан на обработку';
  private int $quantity = 0;
  private int $count = 0;
  private int $cost = 0;
  private array $goods = [];

  /**
   * @return array
   */
  public function getVars(): array
  {
    $vars = get_object_vars( $this );
    unset( $vars[ 'id' ] );
    unset( $vars[ 'quantity' ] );
    unset( $vars[ 'count' ] );
    unset( $vars[ 'cost' ] );
    unset( $vars[ 'goods' ] );

    return $vars;
  }

  /**
   * @param $userId
   */
  public function setUserId( $userId ): void
  {
    $this->userId = is_numeric( $userId ) ? (int)$userId : 0;
  }

  /**
   * @param mixed $status
   */
  public function setStatus( $status ): void
  {
    $this->status = !empty( $status ) ? (string)$status : '';
  }

  /**
   * Заполняет количество позиций, количество товара и его стоимость
   */
  private function calculate(): void
  {
    $this->count = count( $this->goods );
    $this->quantity = 0;
    $this->cost = 0;

    if ( empty( $this->count ) ) {
      return;
    }

    foreach ( $this->goods as $product ) {
      $this->quantity += (int)$product[ 'quantity' ];
      $this->cost += (int)$product[ 'price' ];
    }
  }

  /**
   * @param array $goods
   */
  public function setGoods( array $goods ): void
  {
    $this->goods = is_array( $goods ) ? $goods : [];
    $this->calculate();
  }

  /**
   * @return int
   */
  public function getUserId(): int
  {
    return $this->userId;
  }

  /**
   * @return string
   */
  public function getStatus(): string
  {
    return $this->status;
  }

  /**
   * @return int
   */
  public function getQuantity(): int
  {
    return $this->quantity;
  }

  /**
   * @return int
   */
  public function getCount(): int
  {
    return $this->count;
  }

  /**
   * @return int
   */
  public function getCost(): int
  {
    return $this->cost;
  }

  /**
   * @return array
   */
  public function getGoods(): array
  {
    return $this->goods;
  }
}
