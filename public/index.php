<?php

use app\engine\App;

define( 'ROOT_DIR', dirname( __DIR__ ) . '/' );
define( 'VENDOR_DIR', ROOT_DIR . 'vendor/');
define( 'ENGINE_DIR', ROOT_DIR . 'engine/');
define( 'VIEWS_DIR', ROOT_DIR . 'views/');
define( 'COMPONENTS_DIR', VIEWS_DIR . 'components/');
define( 'LAYOUTS_DIR', VIEWS_DIR . 'layouts/');

require_once VENDOR_DIR . 'autoload.php';

$config = include ENGINE_DIR . 'config.php';

echo App::call()->run( $config );
