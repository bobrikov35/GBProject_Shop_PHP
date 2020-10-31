<?php

namespace app\services;

use app\entities\Entity;
use app\repositories\Repository;

/**
 * Class Paginator
 * @package app\services
 */
class Paginator extends Service
{
  private int $quantity = 0;
  private int $itemsOnPage = 9;
  private string $path = '/product/list/?page=';
  private array $items = [];

  /**
   * @param string $path
   */
  public function setPath( string $path ): void
  {
    $this->path = $path;
  }

  /**
   * @return Entity[]|array
   */
  public function getItems(): array
  {
    return $this->items;
  }

  /**
   * Заполняет список объектами из переданного репозитория
   *
   * @param Repository $repository
   * @param int $page
   */
  public function setItems( Repository $repository, int $page = 1 ): void
  {
    $this->quantity = $repository->getQuantityItems();
    $this->items = $repository->getItemsByPage( $page, $this->itemsOnPage );
  }

  /**
   * Возвращает список ссылок
   *
   * @return array
   */
  public function getUrls(): array
  {
    $urls = [];

    for ( $i = 1; $i <= $this->getPages(); $i++ ) {
      $urls[$i] = $this->path . $i;
    }

    return $urls;
  }

  /**
   * Возвращает количество страниц
   *
   * @return int
   */
  private function getPages(): int
  {
    $result = ceil( $this->quantity / $this->itemsOnPage );

    if ( $result ) {
      return (int)$result;
    }

    return 1;
  }
}
