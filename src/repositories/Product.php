<?php

namespace app\repositories;

use app\entities\{Entity, Product as EProduct};


/**
 * Class Product
 * @package app\repositories
 */
class Product extends Repository
{

    /**
     * О Б Я З А Т Е Л Ь Н Ы Е   Ф У Н К Ц И И
     */

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
     * П У Б Л И Ч Н Ы Е   Ф У Н К Ц И И
     */

    /**
     * Получает все изображения товара
     *
     * @param EProduct|Entity $product
     * @return void
     */
    public function fetchImages(EProduct $product): void
    {
        $images = [];
        $sql = 'SELECT `link` FROM `images` WHERE `id_product` = :id';
        foreach ($this->readTable($sql, [':id' => $product->getId()]) as $row) {
            $images[] = $row['link'];
        }
        $product->setImages($images);
    }

    /**
     * Получает все отзывы о товаре
     *
     * @param EProduct|Entity $product
     * @return void
     */
    public function fetchFeedbacks(EProduct $product): void
    {
        $feedbacks = [];
        $sql = 'SELECT `name`, `email`, `comment` FROM `feedbacks` WHERE `id_product` = :id';
        foreach ($this->getDatabase()->readTable($sql, [':id' => $product->getId()]) as $row) {
            $feedbacks[] = [
                'name' => $row['name'],
                'email' => $row['email'],
                'comment' => $row['comment'],
            ];
        }
        $product->setFeedbacks($feedbacks);
    }

    /**
     * Возвращает товар из базы данных по id
     *
     * @param int $id
     * @return EProduct|Entity|null
     */
    public function getSingle(int $id)
    {
        $product = parent::getSingle($id);
        if (empty($product)) {
            return null;
        }
        $this->fetchImages($product);
        $this->fetchFeedbacks($product);
        return $product;
    }


    /**
     * П Р И В А Т Н Ы Е   Ф У Н К Ц И И
     */

    /**
     * Вставляет товар в базу данных
     *
     * @param EProduct|Entity $product
     * @return int
     */
    protected function insert(Entity $product): int
    {
        if (empty($id = parent::insert($product))) {
            return $id;
        }
        foreach ($product->getImages() as $image) {
            if ($image == '') {
                continue;
            }
            $sql = "INSERT INTO `images` (`link`, `id_product`) VALUES (:link, :id)";
            $this->execute($sql, [':link' => $image, ':id' => $id]);
        }
        return $id;
    }

    /**
     * Изменяет товар в базе данных
     *
     * @param EProduct|Entity $product
     * @return bool
     */
    protected function update(Entity $product): bool
    {
        if (!parent::update($product)) {
            return false;
        }
        $newImages = array_unique($product->getImages());
        $this->fetchImages($product);
        foreach ($product->getImages() as $image) {
            if (in_array($image, $newImages, true)) {
                continue;
            }
            $sql = "DELETE FROM `images` WHERE `id_product` = :id and `link` = :link";
            $this->getDatabase()->execute($sql, [':id' => $product->getId(), ':link' => $image]);
        }
        foreach ($newImages as $image) {
            if (in_array($image, $product->getImages(), true)) {
                continue;
            }
            $sql = "INSERT INTO `images` (`link`, `id_product`) VALUES (:link, :id)";
            $this->getDatabase()->execute($sql, [':id' => $product->getId(), ':link' => $image]);
        }
        return true;
    }

}
