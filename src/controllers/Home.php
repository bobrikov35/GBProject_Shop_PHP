<?php

namespace app\controllers;

/**
 * Class Home
 * @package app\controllers
 */
class Home extends Controller
{
  /**
   * Действие по умолчанию
   */
  protected function default_action()
  {
    $this->redirect('/home/index');
  }

  /**
   * Выводит домашнюю страницу
   *
   * @return string
   */
  protected function index_action(): string
  {
    return $this->render('home.twig', $this->getConfig());
  }

  /**
   * Помощник ide (не вызывать)
   */
  protected function __ideHelper(): void
  {
    /** Функции вызываются динамически (см. class Controller) */
    $this->default_action();
    $this->index_action();
  }
}
