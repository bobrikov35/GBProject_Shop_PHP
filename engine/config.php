<?php

// use app\controllers\{Cart as CCart, Order as COrder, Product as CProduct};
// use app\repositories\{Order as ROrder};
use app\services\{ DB, Paginator, RendererTemplate, Request };
use app\controllers\{ Home };
use app\repositories\{ Product as RProduct, User as RUser };
use app\services\{ Authorization, Cart as SCart, Product as SProduct, User as SUser };

return [
  'title' => 'BRAND',
  'time' => microtime( true ),
  'passwordSol' => 'FR4jO8mH4yAe',
  'controllerDefault' => 'home',
  'actionDefault' => 'default',

  'components' => [
    'database' => [
      'class' => DB::class,
      'config' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'shop',
        'charset' => 'UTF8',
        'port' => 3366,
        'user' => 'root',
        'password' => 'root'
      ]
    ],
    'renderer' => [
      'class' => RendererTemplate::class,
    ],
    'request' => [
      'class' => Request::class,
    ],
    'authorization' => [
      'class' => Authorization::class,
    ],
    'paginator' => [
      'class' => Paginator::class,
    ],
//    'controllerCart' => [
//      'class' => CCart::class,
//    ],
    'controllerHome' => [
      'class' => Home::class,
    ],
//    'controllerOrder' => [
//      'class' => COrder::class,
//    ],
//    'repositoryOrder' => [
//      'class' => ROrder::class,
//    ],
//    'controllerProduct' => [
//      'class' => CProduct::class,
//    ],
    'repositoryProduct' => [
      'class' => RProduct::class,
    ],
    'repositoryUser' => [
      'class' => RUser::class,
    ],
    'serviceCart' => [
      'class' => SCart::class,
    ],
    'serviceProduct' => [
      'class' => SProduct::class,
    ],
    'serviceUser' => [
      'class' => SUser::class,
    ],
  ],
];
